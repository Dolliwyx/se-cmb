<?php

namespace App\Filament\Resources\ROCContinousResource\Pages;

use App\Filament\Resources\ROCContinousResource;
use App\Models\ContinousReport;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateROCContinous extends CreateRecord
{
    protected static string $resource = ROCContinousResource::class;

    public bool $ORExists = false;

    public bool $reacted = true;

    public function updated($propertyName)
    {
        if ($propertyName === 'data.or_number') {
            $this->updateName();
        }

        if ($propertyName === 'data.student_number') {
            $this->updateViaStudentNumber();
        }
    }

    protected function updateName()
    {
        $orNumber = $this->data['or_number'];
        $existingReceipt = ContinousReport::where('or_number', $orNumber)->first();
        if ($existingReceipt) {
            $this->data['payor_name'] = $existingReceipt->payor_name;
            $this->data['student_number'] = $existingReceipt->student_number;
            $this->data['college'] = $existingReceipt->college;
            $this->data['bank_name'] = $existingReceipt->bank_name;
            $this->data['bank_number'] = $existingReceipt->bank_number;
            $this->ORExists = true;
        } else {
            $this->ORExists = false;
        }
    }

    protected function updateViaStudentNumber()
    {
        $studentNumber = str_replace('-', '', $this->data['student_number']);
        $receivableTransaction = DB::table('invoices')->where('StudentNumber', $studentNumber)->first();
        if ($receivableTransaction) {
            $this->data['payor_name'] = $receivableTransaction->StudentName;
            $this->data['amount'] = $receivableTransaction->Amount;
        }
    }
}
