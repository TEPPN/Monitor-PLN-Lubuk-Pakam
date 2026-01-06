<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recap extends Model
{
    use HasFactory;

    // UPDATE BAGIAN INI:
    // Tambahkan request_9m, request_12m, planted_9m, planted_12m
    // Hapus 'request' dan 'planted' yang lama
    protected $fillable = [
        'contract_id', 
        'job', 
        'address', 
        'request_9m',   // Baru
        'request_12m',  // Baru
        'planted_9m',   // Baru
        'planted_12m',  // Baru
        'x_cord', 
        'y_cord', 
        'contract', 
        'executor', 
        'created_by'
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}