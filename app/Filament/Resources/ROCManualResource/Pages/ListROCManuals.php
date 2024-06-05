<?php

namespace App\Filament\Resources\ROCManualResource\Pages;

use App\Filament\Resources\ROCManualResource;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListROCManuals extends ListRecords
{
    protected static string $resource = ROCManualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExcelImportAction::make()->color('primary'),
            Actions\CreateAction::make(),
        ];
    }
}
