<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
    'name', 
    'company_id', 
    'contract_date', 
    'end_date',
    'has_9m',  // <--- Tambahan Baru
    'has_12m', // <--- Tambahan Baru
    'stock_9m', 
    'stock_12m'
];

    use HasFactory;

    protected $casts = [
        'contract_date' => 'date',
        'end_date' => 'date',
        'has_9m' => 'boolean',
        'has_12m' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
