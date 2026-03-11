<?php

namespace App\Filament\Resources\PaymentSummaries\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentSummaryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('customer_payment_unique_id')
                    ->required(),
                DateTimePicker::make('summary_date')
                    ->required(),
                TextInput::make('monthly_rent')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('additional_charge')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('vat')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('previous_due')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('advance')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('discount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
