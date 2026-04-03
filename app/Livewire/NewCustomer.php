<?php

namespace App\Livewire;

use App\Models\AddressField;
use App\Models\BillingInfo;
use App\Models\CustomersAddress;
use App\Models\CustomersInfo;
use App\Models\OfficialInfo;
use App\Models\PackageList;
use App\Models\PPPSecrets;
use App\Models\RouterList;
use App\Models\User;
use App\Services\MikrotikSSHService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Livewire\Component;
use Livewire\WithFileUploads;

class NewCustomer extends Component
{
    use WithFileUploads;

    public $customer_name;

    public $email;

    public $identification_no;

    public $mobile;

    public $alternative_mobile;

    public $profession;

    public $connection_date;

    public $service;

    public $profile;

    public $ip_address;

    public $ppp_remote_ip;

    public $username;

    public $password;

    public $queue_name;

    public $caller_id;

    public $comment;

    public $interface;

    public $bandwidth;

    public $package_name;

    public $monthly_rent;

    public $due_amount;

    public $additional_charge;

    public $discount;

    public $advance;

    public $vat;

    public $total_amount;

    public $client_type;

    public $distribution_location;

    public $description;

    public $note;

    public $connected_by;

    public $security_deposit;

    public $auto_disable;

    public $auto_disable_date;

    public $router_name = '';

    public $auto_disable_month = 0;

    public $billing_type = 'prepaid';

    public $connection_type = 'fiber';

    public $connectivity_type = 'shared';

    public $address = []; // Initialize as an array

    public $data = [];

    public $interfaceNames = []; // Make sure this is an array for Livewire updates

    public $profileNames = []; // Make sure this is an array for Livewire updates

    public $photo_url;

    // for on load
    public $addressFields;

    public $routers;

    public $packages;

    public $users; // Declare the property to hold address fields

    public function mount()
    {
        if (! auth()->user()->can('create-customer')) {
            abort(403, 'Unauthorized action.');
        }

        return true;
    }

    public function rules()
    {
        // Start with the base rules
        $rules = [
            'customer_name' => 'required|min:3|max:255',
            'mobile' => 'required|digits:11',
            'email' => 'nullable|email',
            'alternative_mobile' => 'nullable|digits:11',
            'identification_no' => 'nullable|min:9|max:17',
            'router_name' => 'nullable|required_with:service',
            'service' => 'nullable|required_with:router_name',
            'interface' => 'nullable|required_if:service,static',
            'ip_address' => 'nullable|required_if:service,static|ip',
            'bandwidth' => 'nullable|required_if:service,static|regex:/^\d+(M|K)\/\d+(M|K)$/',
            'caller_id' => 'nullable|mac_address',
            'queue_name' => 'nullable|required_if:service,static|string|max:25',
            'profile' => 'nullable|required_if:service,pppoe|string|max:25',
            'username' => 'nullable|required_if:service,pppoe|string|max:25',
            'monthly_rent' => 'required|numeric',
            'connected_by' => 'required',
            'billing_type' => 'required',
            'connection_type' => 'required',
            'connectivity_type' => 'required',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Add dynamic address rules if they exist
        if ($this->addressFields) {
            // dd($this->addressFields);
            foreach ($this->addressFields as $addressField) {
                if ($addressField->required == true) {
                    // Create rules for each required address field
                    $rules['address.'.$addressField->label] = 'required|string|max:255';
                }
            }
        }

        return $rules; // Return the combined rules
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function packageName($value)
    {
        $this->package_name = $this->profile;
        $this->calculateTotal('package_name');
    }

    public function calculateTotal($value)
    {
        // If package name is set, get the package price
        if ($this->package_name) {
            $package = PackageList::where('package', $this->package_name)
                ->where('router_name', $this->router_name)
                ->first();
            $this->monthly_rent = $package?->price ?? '';
        }

        // Prevent modifying 'monthly_rent' if 'package_name' is set and warn the user
        if ($value == 'monthly_rent' && $this->package_name) {
            $this->addError('monthly_rent', 'First unset Package Name before changing Monthly Rent.');

            return;
        }

        // Recalculate total if any of the relevant fields change
        if (in_array($value, ['monthly_rent', 'due_amount', 'additional_charge', 'discount', 'advance', 'vat']) || $value == 'package_name') {
            $subtotal = (float) $this->monthly_rent + (float) $this->due_amount + (float) $this->additional_charge;
            $vatAmount = ($subtotal * (float) $this->vat) / 100;
            $this->total_amount = $subtotal + $vatAmount - ((float) $this->advance + (float) $this->discount);
        }
    }

    public function getInterface($propertyName)
    {
        if (in_array($propertyName, ['service', 'router_name'])) {
            $this->data = [
                'service' => $this->service,
                'router_name' => $this->router_name,
            ];
            // Proceed only if service is static and router_name is set
            // auto disable date will be set only if router_name is set
            if ($this->router_name) {
                $this->auto_disable = true;
                $this->auto_disable_date = now()->addDays(30)->format('Y-m-d');
            } else {
                $this->auto_disable = false;
                $this->auto_disable_date = $this->ip_address = $this->interface = $this->queue_name = $this->profile = $this->username = $this->password = $this->ppp_remote_ip = $this->caller_id = $this->bandwidth = $this->service = null;
            }
            // Proceed only if service is static and router_name is set than fetch interfaces and profile
            if ($this->service == 'static' && $this->router_name) {
                try {
                    $router = RouterList::where('router_name', $this->router_name)->first();
                    $mikrotikSSHService = new MikrotikSSHService(
                        $router->ip_address,
                        $router->ssh_port,
                        $router->username,
                        $router->password
                    );

                    $interfaces = $mikrotikSSHService->executeCommand('/interface print without-paging terse where type="ether" or type="vlan"');

                    // Clear interfaceNames before updating
                    $this->interfaceNames = [];

                    foreach (explode("\n", $interfaces) as $line) {
                        if (preg_match('/name=([^\s]+)/', $line, $matches)) {
                            $this->interfaceNames[] = $matches[1]; // Update the component's array
                        }
                    }
                } catch (\Exception $e) {
                    flash()->error('Router '.$e->getMessage().' is not connected!');
                }

                $this->profileNames = [];
                $this->username = $this->password = $this->ppp_remote_ip = $this->caller_id = null;

                return;
            } elseif ($this->service == 'pppoe' && $this->router_name) {
                // Proceed only if service is pppoe and router_name is set
                try {
                    $router = RouterList::where('router_name', $this->router_name)->first();

                    $mikrotikSSHService = new MikrotikSSHService(
                        $router->ip_address,
                        $router->ssh_port,
                        $router->username,
                        $router->password
                    );

                    $profiles = $mikrotikSSHService->executeCommand('/ppp profile print without-paging terse');
                    // \dd($profiles);

                    // Clear profileNames before updating
                    $this->profileNames = [];

                    foreach (explode("\n", $profiles) as $line) {
                        if (preg_match('/name=([^\s]+)/', $line, $matches)) {
                            $this->profileNames[] = $matches[1]; // Update the component's array
                        }
                    }
                } catch (\Exception $e) {
                    flash()->error('Router '.$e->getMessage().' is not connected!');
                }

                $this->interfaceNames = [];
                $this->ip_address = $this->queue_name = $this->caller_id = $this->bandwidth = null;

                return;
            } else {
                $this->interfaceNames = $this->profileNames = [];
                $this->auto_disable_date = $this->ip_address = $this->queue_name = $this->username = $this->password = $this->ppp_remote_ip = $this->caller_id = $this->bandwidth = $this->service = null;

                return;
            }
        }
    }

    public function removePhoto()
    {
        $this->photo_url = null;
        flash()->warning('Image Removed successfully!');
    }

    public function save()
    {
        try {
            $this->validate();

            if ($this->service == 'pppoe') {
                try {
                    $router = RouterList::where('router_name', $this->router_name)->first();
                    $mikrotikSSHService = new MikrotikSSHService(
                        $router->ip_address,
                        $router->ssh_port,
                        $router->username,
                        $router->password
                    );
                    if ($this->ppp_remote_ip != '') {
                        $interfaces = $mikrotikSSHService->executeCommand("/ppp secret add name=\"{$this->username}\" password=\"{$this->password}\" service=\"{$this->service}\" profile=\"{$this->profile}\" comment=\"{$this->comment}\" remote-address=\"{$this->ppp_remote_ip}\" caller-id=\"{$this->caller_id}\" disabled=yes");
                    } else {
                        $interfaces = $mikrotikSSHService->executeCommand("/ppp secret add name=\"{$this->username}\" password=\"{$this->password}\" service=\"{$this->service}\" profile=\"{$this->profile}\" comment=\"{$this->comment}\" caller-id=\"{$this->caller_id}\" disabled=yes");
                    }

                    if ($interfaces != '') {
                        $this->dispatch('mikrotikError', $interfaces, 'error');
                    } else {
                        $this->createUser();
                    }

                } catch (\Exception $e) {
                    flash()->error('Router '.$e->getMessage().' is not connected!');
                }
            } elseif ($this->service == 'static') {
                try {
                    $router = RouterList::where('router_name', $this->router_name)->first();
                    $mikrotikSSHService = new MikrotikSSHService(
                        $router->ip_address,
                        $router->ssh_port,
                        $router->username,
                        $router->password
                    );
                    $interfaces = $mikrotikSSHService->executeCommand("/queue simple add name=\"{$this->queue_name}\" profile=\"{$this->profile}\" address=\"{$this->ip_address}\" max-limit=\"{$this->bandwidth}\" comment=\"{$this->comment}\" disabled=yes");

                    if ($interfaces != '') {
                        $this->dispatch('mikrotikError', $interfaces, 'error');
                    } else {
                        $this->createUser();
                    }
                } catch (\Exception $e) {
                    flash()->error('Router '.$e->getMessage().' is not connected!');
                }
            } else {
                $this->createUser();
            }
        } catch (ValidationException $e) {
            // Validation failed, extract error messages
            $errors = $e->validator->errors()->all();

            // Loop through the errors and each as a toast notification
            foreach ($errors as $error) {
                flash()->error($error);
            }

            // Re-throw the validation exception to allow @error directive to work
            throw $e;
        } catch (\Exception $e) {
            flash()->error('Error: '.$e->getMessage());
        }
    }

    public function createUser()
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Generate a unique filename and define the path
            $filename = uniqid().'.jpg';
            $path = 'customer-images/'.$filename;

            if ($this->photo_url) {
                $image_file = $this->photo_url->getRealPath();
                // create new manager instance with desired driver
                $manager = new ImageManager(new Driver);
                // read image from file system
                $image = $manager->read($image_file);
                // Image resize
                $image->resize(300, 300);
                // save modified image in new format
                $image->save(public_path("$path"));
            }

            if (auth()->user()->can('mikrotik-user-create')) {
                // create ppp_user table record
                $pppUserCheck = PPPSecrets::where('username', $this->username)->orwhere('username', $this->queue_name)->first();

                // Check if this PPP user is already linked to a customer
                if ($pppUserCheck && CustomersInfo::where('ppp_user_id', $pppUserCheck->id)->exists()) {
                    $this->dispatch('customerError', 'This PPP user is already linked to a customer', 'error');
                }

                if ($pppUserCheck) {
                    $pppUser = $pppUserCheck;
                } else {
                    if ($this->router_name) {
                        $pppUser = new PPPSecrets;
                        $pppUser->router_name = $this->router_name;
                        $pppUser->username = ($this->username != '') ? $this->username : $this->queue_name;
                        $pppUser->password = $this->password;
                        $pppUser->service = $this->service;
                        $pppUser->profile = ($this->profile != '') ? $this->profile : $this->interface;
                        $pppUser->bandwidth = $this->bandwidth;
                        $pppUser->comment = $this->comment;
                        $pppUser->caller_id = $this->caller_id;
                        $pppUser->ppp_remote_ip = ($this->ppp_remote_ip != '') ? $this->ppp_remote_ip : $this->ip_address;
                        $pppUser->save();
                    }
                }
            }

            // create customers_info table record
            $lastCustomer = CustomersInfo::orderBy('id', 'desc')->value('customer_unique_id');
            if ($lastCustomer) {
                $lastId = (int) substr($lastCustomer, 5); // 'FCNET' + 3 digits
                $newId = 'FCNET'.($lastId + 1); // create new id
            } else {
                $newId = 'FCNET100'; // if no record found, start from 100
            }
            $customer = new CustomersInfo;
            $customer->customer_unique_id = $newId;
            $customer->customer_name = $this->customer_name;
            $customer->email = $this->email;
            $customer->identification_no = $this->identification_no;
            $customer->photo_url = $this->photo_url ? $path : null;
            $customer->mobile = '88'.$this->mobile;
            $customer->alternative_mobile = '88'.$this->alternative_mobile;
            $customer->profession = $this->profession;
            $customer->ppp_user_id = $pppUser->id ?? null;
            $customer->connection_date = $this->connection_date;

            // Get package_id based on selected package name and router
            $assignedPackageId = null;
            if ($this->package_name) {
                $pkg = PackageList::where('package', $this->package_name);
                if ($this->router_name) {
                    $pkg = $pkg->where('router_name', $this->router_name);
                }
                $assignedPackageId = $pkg->first()?->id;
            }
            $customer->package_id = $assignedPackageId;

            $customer->save();

            foreach ($this->address as $key => $value) {
                // for each address field, create a new record
                $customerAddress = new CustomersAddress;
                $customerAddress->customer_address_unique_id = $customer->customer_unique_id;
                $customerAddress->label_name = $key;

                // input type should be fetched from the address field table
                $inputType = AddressField::where('label', $key)->first();
                if ($inputType) {
                    $customerInputType = 'input_type_'.$inputType->input_type;
                    $customerAddress->$customerInputType = $value;
                }
                $customerAddress->save();
            }

            $customerBilling = new BillingInfo;
            $customerBilling->customer_bill_unique_id = $customer->customer_unique_id;
            $customerBilling->billing_type = $this->billing_type;
            $customerBilling->monthly_rent = $this->normalizeValue($this->monthly_rent);
            $customerBilling->due_amount = $this->normalizeValue($this->due_amount);
            $customerBilling->additional_charge = $this->normalizeValue($this->additional_charge);
            $customerBilling->discount = $this->normalizeValue($this->discount);
            $customerBilling->advance = $this->normalizeValue($this->advance);
            $customerBilling->vat = $this->normalizeValue($this->vat);
            $customerBilling->auto_disable = $this->auto_disable ?? 1;
            $customerBilling->auto_disable_date = $this->auto_disable_date ?? null;
            $customerBilling->auto_disable_month = $this->auto_disable_month;
            $customerBilling->total_amount = $this->total_amount;
            $customerBilling->due_amount = $this->total_amount;
            $customerBilling->save();

            // create Official information table record
            $customerOfficial = new OfficialInfo;
            $customerOfficial->customer_office_unique_id = $customer->customer_unique_id;
            $customerOfficial->billing_type = $this->billing_type;
            $customerOfficial->connection_type = $this->connection_type;
            $customerOfficial->connectivity_type = $this->connectivity_type;
            $customerOfficial->client_type = $this->client_type;
            $customerOfficial->distribution_location = $this->distribution_location;
            $customerOfficial->description = $this->description;
            $customerOfficial->note = $this->note;
            $customerOfficial->security_deposit = $this->normalizeValue($this->security_deposit);
            $customerOfficial->connected_by = $this->connected_by;
            $customerOfficial->save();

            // Commit transaction if all goes well
            DB::commit();

            flash()->success('Customer created successfully!');

            // Clear form
            $this->reset();

            // Redirect to customers list
            return redirect('/customers');

        } catch (\Exception $e) {
            // Rollback transaction if any error occurs
            DB::rollBack();

            // Delete the image if it was saved
            // if (Storage::disk('public')->exists($path)) {
            //     Storage::disk('public')->delete($path);
            // }
            if (file_exists(public_path($path))) {
                unlink(public_path($path));
            }
            flash()->error('Error: '.$e->getMessage());
        }
    }

    private function normalizeValue($value)
    {
        return $value === '' || $value === null ? 0 : $value;
    }

    public function render()
    {
        $this->addressFields = AddressField::orderBy('order', 'asc')->get();
        $this->routers = RouterList::select('router_name')->where('action', 'connected')->get();
        $this->packages = PackageList::select('price', 'package')
            ->when($this->router_name, fn ($q) => $q->where('router_name', $this->router_name))
            ->get();
        // $users = User::permission('create-user')->get();
        $this->users = User::all();

        return view('livewire.new-customer')->layout('layouts.app');
    }
}
