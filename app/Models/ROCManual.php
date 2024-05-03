<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ROCManual extends Model
{
    use HasFactory;

    protected $fillable = [
        'or_number',
        'payor_name',
        'student_number',
        'college',
        'transaction_code',
        'amount',
        'total_amount',
        'remarks',
        'cheque_bank',
        'cheque_number',
    ];
}
