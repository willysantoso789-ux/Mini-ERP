<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToUser;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory, SoftDeletes, BelongsToUser;

    protected $fillable = [
        'wallet_id',    
        'category_id',
        'dream_id',
        'description',
        'amount',
        'transaction_date',
        'image',
        'user_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function dream()
    {
        return $this->belongsTo(Dream::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
