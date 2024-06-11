<?php

namespace App\Filament\Resources\ROCContinousResource\Pages;

use App\Filament\Resources\ROCContinousResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditROCContinous extends EditRecord
{
    protected static string $resource = ROCContinousResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()->extraAttributes(['style' => 'color: white;']),
        ];
    }
}
