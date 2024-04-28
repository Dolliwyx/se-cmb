<?php

namespace App\Filament\Resources\ROCContinousResource\Pages;

use App\Filament\Resources\ROCContinousResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListROCContinouses extends ListRecords
{
    protected static string $resource = ROCContinousResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
