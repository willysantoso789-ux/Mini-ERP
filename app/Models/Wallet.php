<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    /** @use HasFactory<\Database\Factories\WalletFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
