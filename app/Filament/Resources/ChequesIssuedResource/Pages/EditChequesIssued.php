<?php

namespace App\Filament\Resources\ChequesIssuedResource\Pages;

use App\Filament\Resources\ChequesIssuedResource;
use App\Models\Payable;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

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

    public function updated($propertyName)
    {
        if ($propertyName === 'data.BUR') {
            $this->updateName();
        }
    }

    protected function updateName()
    {
        $bur = $this->data['BUR'];
        $existingBUR = Payable::where('BUR', $bur)->first();
        $particulars = DB::table('particular')->where('BUR', $bur)->sum('ParticularAmount');
        if ($existingBUR) {
            $this->data['payee'] = $existingBUR->SupplierName;
            $this->data['amount'] = number_format($particulars, 2, '.', '');
        } else {
            $this->data['payee'] = null;
            $this->data['amount'] = null;
        }
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $bur = $data['BUR'];
        $existingBUR = Payable::where('BUR', $bur)->first();
        $particulars = DB::table('particular')->where('BUR', $existingBUR->BUR)->sum('ParticularAmount');

        if ($existingBUR) {
            $data['payee'] = $existingBUR->SupplierName;
            $data['amount'] = number_format($particulars, 2, '.', '');
        } else {
            $data['payee'] = null;
            $data['amount'] = null;
        }

        return $data;
    }
}
