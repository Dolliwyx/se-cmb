<?php

namespace App\Filament\Resources\ROCContinousResource\Pages;

use App\Filament\Resources\ROCContinousResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewROCContinous extends ViewRecord
{
    protected static string $resource = ROCContinousResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->extraAttributes(['style' => 'color: white;']),
        ];
    }
}
