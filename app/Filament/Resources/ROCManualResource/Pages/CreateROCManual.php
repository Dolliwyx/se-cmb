<?php

namespace App\Filament\Resources\ROCManualResource\Pages;

use App\Filament\Resources\ROCManualResource;
use App\Models\ManualReport;
use Filament\Resources\Pages\CreateRecord;

class CreateROCManual extends CreateRecord
{
    protected static string $resource = ROCManualResource::class;

    public bool $ORExists = false;

    public function updated($propertyName)
    {
        if ($propertyName === 'data.or_number') {
            $this->updateName();
        }
    }

    protected function updateName()
    {
        $orNumber = $this->data['or_number'];
        $existingReceipt = ManualReport::where('or_number', $orNumber)->first();
        if ($existingReceipt) {
            $this->data['payor_name'] = $existingReceipt->payor_name;
            $this->data['student_number'] = $existingReceipt->student_number;
            $this->data['college'] = $existingReceipt->college;
            $this->data['bank_name'] = $existingReceipt->bank_name;
            $this->data['bank_number'] = $existingReceipt->bank_number;
            $this->ORExists = true;
        } else {
            $this->data['payor_name'] = null;
            $this->data['student_number'] = null;
            $this->data['college'] = null;
            $this->data['bank_name'] = null;
            $this->data['bank_number'] = null;
            $this->ORExists = false;
        }
    }
}
