<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToUser;

class Wallet extends Model
{
    /** @use HasFactory<\Database\Factories\WalletFactory> */
    use HasFactory, SoftDeletes, BelongsToUser;

    protected $fillable = [
        'name',
        'type',
        'user_id'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getBalanceAttribute()
    {
        $income = $this->transactions()->whereHas('category', function($q) {
            $q->where('type', 'income');
        })->sum('amount');

        $expense = $this->transactions()->whereHas('category', function($q) {
            $q->where('type', 'expense');
        })->sum('amount');

        return $income - $expense;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
