<?php

namespace App\Filament\Resources\ChequesIssuedResource\Pages;

use App\Filament\Resources\ChequesIssuedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChequesIssueds extends ListRecords
{
    protected static string $resource = ChequesIssuedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->extraAttributes(['style' => 'color: white;']),
        ];
    }
}
