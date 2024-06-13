<?php

namespace App\Filament\Resources\ChequesIssuedResource\Pages;

use App\Filament\Resources\ChequesIssuedResource;
use App\Models\Payable;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateChequesIssued extends CreateRecord
{
    protected static string $resource = ChequesIssuedResource::class;

    public function updated($propertyName)
    {
        if ($propertyName === 'data.bur') {
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
}
