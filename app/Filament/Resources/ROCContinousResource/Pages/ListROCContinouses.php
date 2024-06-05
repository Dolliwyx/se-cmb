<?php

namespace App\Filament\Resources\ROCContinousResource\Pages;

use App\Filament\Resources\ROCContinousResource;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListROCContinouses extends ListRecords
{
    protected static string $resource = ROCContinousResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExcelImportAction::make()->color('primary'),
            Actions\CreateAction::make(),
        ];
    }
}
