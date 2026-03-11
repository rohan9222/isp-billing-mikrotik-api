<?php

namespace App\Filament\Resources\PaymentSummaries\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PaymentSummaryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('customer_payment_unique_id'),
                TextEntry::make('summary_date')
                    ->dateTime(),
                TextEntry::make('monthly_rent')
                    ->numeric(),
                TextEntry::make('additional_charge')
                    ->numeric(),
                TextEntry::make('vat')
                    ->numeric(),
                TextEntry::make('previous_due')
                    ->numeric(),
                TextEntry::make('advance')
                    ->numeric(),
                TextEntry::make('discount')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
