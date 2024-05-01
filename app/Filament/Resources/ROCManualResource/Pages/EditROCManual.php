<?php

namespace App\Filament\Resources\ROCManualResource\Pages;

use App\Filament\Resources\ROCManualResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditROCManual extends EditRecord
{
    protected static string $resource = ROCManualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
