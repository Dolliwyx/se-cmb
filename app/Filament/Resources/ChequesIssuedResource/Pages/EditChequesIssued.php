<?php

namespace App\Filament\Resources\ChequesIssuedResource\Pages;

use App\Filament\Resources\ChequesIssuedResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChequesIssued extends EditRecord
{
    protected static string $resource = ChequesIssuedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()->extraAttributes(['style' => 'color: white;']),
        ];
    }
}
