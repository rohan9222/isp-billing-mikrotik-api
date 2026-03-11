<?php

namespace App\Filament\Resources\PaymentSummaries\Pages;

use App\Filament\Resources\PaymentSummaries\PaymentSummaryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPaymentSummaries extends ListRecords
{
    protected static string $resource = PaymentSummaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
