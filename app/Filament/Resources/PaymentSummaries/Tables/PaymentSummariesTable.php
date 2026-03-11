<?php

namespace App\Filament\Resources\PaymentSummaries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentSummariesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer_payment_unique_id')
                    ->label('Customer ID')
                    ->searchable(),
                TextColumn::make('id')
                    ->label('Invoice ID')
                    ->searchable(),
                TextColumn::make('summary_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('monthly_rent')
                    ->label('Monthly Rent')
                    ->money('BDT'),
                TextColumn::make('additional_charge')
                    ->label('Extra')
                    ->money('BDT'),
                TextColumn::make('discount')
                    ->label('Discount')
                    ->money('BDT'),
                TextColumn::make('advance')
                    ->label('Advance')
                    ->money('BDT'),
                TextColumn::make('vat')
                    ->label('VAT')
                    ->money('BDT'),
                TextColumn::make('previous_due')
                    ->label('Prev. Due')
                    ->money('BDT'),
                // TextColumn::make('total')
                //     ->label('Total')
                //     ->sum('monthly_rent', 'additional_charge', 'discount', 'advance', 'vat', 'previous_due')
                //     ->money('BDT'),
                // TextColumn::make('paid')
                //     ->label('Paid')
                //     ->money('BDT'),
                // TextColumn::make('due')
                //     ->label('Due')
                //     ->money('BDT'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
