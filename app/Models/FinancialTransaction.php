<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_type',
        'or_number',
        'payor_name',
        'student_number',
        'college',
        'transaction_code',
        'amount',
        'total_amount',
        'remarks',
        'bank_name',
        'bank_number'
    ];
}
