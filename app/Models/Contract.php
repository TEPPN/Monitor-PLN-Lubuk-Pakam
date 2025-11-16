<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = ['name', 'company_id', 'contract_date', 'pole_size', 'stock'];

    use HasFactory;

    protected $casts = [
        'contract_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
