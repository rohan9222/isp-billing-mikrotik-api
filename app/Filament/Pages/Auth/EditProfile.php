<?php

namespace App\Filament\Pages\Auth;

use App\Models\CustomersInfo;
use Filament\Actions\Action;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class EditProfile extends BaseEditProfile
{
    public static function isSimple(): bool
    {
        return false;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $customerInfo = CustomersInfo::where('ppp_user_id', auth()->id())->first();
        $customerAddresses = [];
        foreach ($customerInfo->customerAddress as $address) {
            $customerAddresses[] = $address->label_name.' : '.($address->input_type_text ?? $address->input_type_dropdown ?? $address->input_type_textarea);
        }
        $full_address = implode(', ', $customerAddresses);

        if ($customerInfo) {
            $data['user_id'] = $customerInfo->customer_unique_id;
            $data['pppoe_secret'] = auth()->user()->username;
            $data['customer_name'] = $customerInfo->customer_name;
            $data['contact_person'] = $customerInfo->contact_person;
            $data['parents_name'] = $customerInfo->parents_name;
            $data['spouse_name'] = $customerInfo->spouse_name;
            $data['email'] = $customerInfo->email;

            // Remove 880 prefix for the form if present
            $data['mobile'] = preg_replace('/^880/', '', $customerInfo->mobile);
            $data['alternative_mobile'] = preg_replace('/^880/', '', $customerInfo->alternative_mobile);

            $data['profession'] = $customerInfo->profession;
            $data['identification_no'] = $customerInfo->identification_no;
            $data['photo_url'] = $customerInfo->photo_url;
            $data['full_address'] = $full_address;
        }

        return $data;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer Information')
                    ->headerActions([
                        Action::make('status')
                            ->label(fn () => auth()->user()->status)
                            ->color(fn () => (auth()->user()->status === 'active') ? 'success' : ((auth()->user()->status === 'free') ? 'info' : 'warning'))
                            ->icon(fn () => auth()->user()->status === 'active' ? 'heroicon-m-check-circle' : ((auth()->user()->status === 'free') ? 'heroicon-m-clock' : 'heroicon-m-x-circle')),
                    ])
                    ->schema([
                        TextInput::make('user_id')
                            ->label('User ID')
                            ->disabled()
                            ->dehydrated(),
                        TextInput::make('pppoe_secret')
                            ->label('PPPOE Secret')
                            ->disabled()
                            ->dehydrated(),
                        TextInput::make('customer_name')
                            ->label('Customer Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->required()
                            ->email()
                            ->maxLength(255),
                        TextInput::make('mobile')
                            ->label('Mobile')
                            ->required()
                            ->prefix('880')
                            ->numeric()
                            ->length(10)
                            ->validationMessages([
                                'length' => 'The mobile number must be exactly 10 digits after 880.',
                            ]),
                        TextInput::make('alternative_mobile')
                            ->label('Alternative Mobile')
                            ->prefix('880')
                            ->numeric()
                            ->length(10)
                            ->validationMessages([
                                'length' => 'The alternative mobile number must be exactly 10 digits after 880.',
                            ]),
                    ])->columns(2),
                Section::make('Contact & Identification')
                    ->schema([
                        TextInput::make('contact_person')
                            ->label('Contact Person')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('parents_name')
                            ->label('Parents Name')
                            ->maxLength(255),
                        TextInput::make('spouse_name')
                            ->label('Spouse Name')
                            ->maxLength(255),
                        TextInput::make('profession')
                            ->label('Profession')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('identification_no')
                            ->label('Identification No')
                            ->required()
                            ->maxLength(255),
                        FileUpload::make('photo_url')
                            ->label('Photo')
                            ->image()
                            ->imageEditor()
                            ->disk('direct_public')
                            ->directory('customer-images')
                            ->required(),
                    ])->columns(2),
                Section::make('Address')
                    ->schema([
                        TextInput::make('full_address')
                            ->label('Address')
                            ->disabled()
                            ->dehydrated(),
                    ]),
            ]);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Prepend 880 to mobile numbers before saving
        if (! empty($data['mobile'])) {
            $data['mobile'] = '880'.ltrim($data['mobile'], '0');
        }
        if (! empty($data['alternative_mobile'])) {
            $data['alternative_mobile'] = '880'.ltrim($data['alternative_mobile'], '0');
        }

        $customerFields = [
            'customer_name',
            'contact_person',
            'parents_name',
            'spouse_name',
            'email',
            'mobile',
            'alternative_mobile',
            'profession',
            'identification_no',
            'photo_url',
            'address',
        ];

        $customerData = Arr::only($data, $customerFields);

        if (isset($data['email'])) {
            $record->update(['email' => $data['email']]);
        }

        // Update CustomersInfo
        $customerInfo = CustomersInfo::where('ppp_user_id', $record->id)->first();
        if ($customerInfo) {
            $customerInfo->update($customerData);
        }

        return $record;
    }
}
