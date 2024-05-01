<?php

namespace App\Filament\Resources\ROCManualResource\Pages;

use App\Filament\Resources\ROCManualResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewROCManual extends ViewRecord
{
    protected static string $resource = ROCManualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
