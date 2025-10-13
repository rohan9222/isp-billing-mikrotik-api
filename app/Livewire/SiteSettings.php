<?php

namespace App\Livewire;

use App\Models\SiteSetting;

use Livewire\WithFileUploads;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use Livewire\Component;

class SiteSettings extends Component
{
    use WithFileUploads;

    public $site_name, $site_title, $site_email, $site_phone, $site_address, $site_logo, $preview_site_logo, $site_icon, $preview_site_icon, $site_favicon, $preview_site_favicon, $site_invoice_prefix,$disable_check_no,$disable_check_days;

    public function mount()
    {
        if (! hasAccess(['Super Admin'], ['site-settings'])) {
            abort(403, 'Unauthorized action.');
        }
        
        $site_settings = SiteSetting::first();
        $this->site_name = $site_settings->site_name ?? '';
        $this->site_title = $site_settings->site_title ?? '';
        $this->site_email = $site_settings->site_email ?? '';
        $this->site_phone = $site_settings->site_phone ?? '';
        $this->site_address = $site_settings->site_address ?? '';
        $this->preview_site_logo = $site_settings->site_logo ?? '';
        $this->preview_site_icon = $site_settings->site_icon ?? '';
        $this->preview_site_favicon = $site_settings->site_favicon ?? '';
        $this->site_invoice_prefix = $site_settings->site_invoice_prefix ?? '';
        $this->disable_check_no = $site_settings->disable_check_no ?? '';
        $this->disable_check_days = $site_settings->disable_check_days ?? '';
    }

    public function render()
    {
        return view('livewire.site-settings')->layout('layouts.app');
    }

    public function removePhoto($img)
    {
        if ($img === 'logo') {
            $this->site_logo = null;
        } elseif ($img === 'icon') {
            $this->site_icon = null;
        } elseif ($img === 'favicon') {
            $this->site_favicon = null;
        } else {
            flash()->error('Invalid image type!');
        }
    }

    public function removePreviewPhoto($img)
    {
        if ($img === 'logo') {
            if (!empty($this->preview_site_logo)) {
                $filePath = public_path($this->preview_site_logo);
                if (file_exists($filePath)) {
                    unlink($filePath);
                } else {
                    flash()->error('Logo image not found!');
                    return;
                }
            }
            SiteSetting::where('id', 1)->update(['site_logo' => null]);
            $this->preview_site_logo = null;
            flash()->warning('Logo image removed successfully!');
        } elseif ($img === 'icon') {
            if (!empty($this->preview_site_icon)) {
                $filePath = public_path($this->preview_site_icon);
                if (file_exists($filePath)) {
                    unlink($filePath);
                } else {
                    flash()->error('Logo image not found!');
                    return;
                }
            }
            SiteSetting::where('id', 1)->update(['site_icon' => null]);
            $this->preview_site_icon = null;
            flash()->warning('Icon image removed successfully!');
        } elseif ($img === 'favicon') {
            if (!empty($this->preview_site_favicon)) {
                $filePath = public_path($this->preview_site_favicon);
                if (file_exists($filePath)) {
                    unlink($filePath);
                } else {
                    flash()->error('Logo image not found!');
                    return;
                }
            }
            SiteSetting::where('id', 1)->update(['site_favicon' => null]);
            $this->preview_site_favicon = null;
            flash()->warning('Favicon image removed successfully!');
        } else {
            flash()->error('Invalid image type!');
        }
    }

    public function updateSettings(){
        $this->validate([
            'site_name'          => 'max:11',
            'site_title'         => 'max:100',
            'site_email'         => 'required|email',
            'site_phone'         => 'required|digits:11',
            'site_address'       => 'required',
            'site_invoice_prefix'=> 'required',
            'site_logo'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'site_icon'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'site_favicon'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'disable_check_no'   => 'nullable|integer|min:0',
            'disable_check_days' => 'nullable|integer|min:0',
        ], [
            'disable_check_no.*'   => 'Check number must be valid.',
            'disable_check_days.*' => 'Check days must be valid.',
        ]);

        // Extra custom validation for multiplication < 30
        if (($this->disable_check_no ?? 0) * ($this->disable_check_days ?? 0) >= 30) {
            $this->addError('disable_check_no', 'The multiplication of check no and days must be less than 30.');
            return; // stop execution if validation fails
        }

        $site_settings = SiteSetting::first();
        $site_settings->site_name = $this->site_name;
        $site_settings->site_title = $this->site_title;
        $site_settings->site_email = $this->site_email;
        $site_settings->site_phone = $this->site_phone;
        $site_settings->site_address = $this->site_address;
        $site_settings->site_invoice_prefix = $this->site_invoice_prefix;
        $site_settings->disable_check_no = $this->disable_check_no;
        $site_settings->disable_check_days = $this->disable_check_days;

        if ($this->site_logo) {
            $filename = 'logo' . uniqid() . '.png';
            $path = 'images/' . $filename;

            $image_file =$this->site_logo->getRealPath();
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image_file);
            $image->save(public_path("$path"));
            $site_settings->site_logo = $path;
        }

        if ($this->site_icon) {
            $filename ='icon' . uniqid() . '.png';
            $path = 'images/' . $filename;

            $image_file =$this->site_icon->getRealPath();
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image_file);
            $image->save(public_path("$path"));
            $site_settings->site_icon = $path;
        }

        if ($this->site_favicon) {
            $filename = 'favicon' . uniqid() . '.png';
            $path = 'images/' . $filename;

            $image_file =$this->site_favicon->getRealPath();
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image_file);
            $image->resize(600, 600);
            $image->save(public_path("$path"));
            $site_settings->site_favicon = $path;
        }
        $site_settings->save();
        flash()->success('Site settings updated successfully!');
    }
}
