<?php

namespace App\Filament\Resources\ChequesIssuedResource\Pages;

use App\Filament\Resources\ChequesIssuedResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewChequesIssued extends ViewRecord
{
    protected static string $resource = ChequesIssuedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->extraAttributes(['style' => 'color: white;']),
        ];
    }
}
