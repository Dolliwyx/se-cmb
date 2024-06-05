<?php

namespace App\Models;

class ContinousReport extends FinancialTransaction
{
    protected $table = 'financial_transactions';

    protected $attributes = [
        'transaction_type' => 0,
    ];

    protected $fillable = [
        'or_number',
        'payor_name',
        'student_number',
        'college',
        'transaction_code',
        'amount',
        'remarks',
        'bank_name',
        'bank_number',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $existingReceipt = static::where('or_number', $model->or_number)->first();
            if ($existingReceipt) {
                $model->payor_name = $existingReceipt->payor_name;
                $model->student_number = $existingReceipt->student_number;
                $model->college = $existingReceipt->college;
                $model->bank_name = $existingReceipt->bank_name;
                $model->bank_number = $existingReceipt->bank_number;
            }
        });
    }
}
