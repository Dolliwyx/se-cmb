<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ChequesIssued extends Model
{
    use HasFactory;

    protected $fillable = [
        'BUR',
        'cheque_number',
        'payee',
        'nature',
        'amount',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $existingBUR = Payable::where('BUR', $model->BUR)->first();
            $particulars = DB::table('particular')->where('BUR', $model->BUR)->sum('ParticularAmount');
            if ($existingBUR) {
                $model->BUR = $existingBUR->BUR;
                $model->payee = $existingBUR->SupplierName;
                $model->amount = number_format($particulars, 2, '.', '');
            } else {
                $model->BUR = null;
                $model->payee = null;
                $model->amount = null;
            }
        });
    }
}
