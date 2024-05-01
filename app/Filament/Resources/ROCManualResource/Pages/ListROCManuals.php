<?php

namespace App\Filament\Resources\ROCManualResource\Pages;

use App\Filament\Resources\ROCManualResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListROCManuals extends ListRecords
{
    protected static string $resource = ROCManualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
