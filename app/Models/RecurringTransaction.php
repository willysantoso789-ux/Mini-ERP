<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToUser;

class RecurringTransaction extends Model
{
    use HasFactory, BelongsToUser;

    protected $fillable = [
        'wallet_id',
        'category_id',
        'description',
        'amount',
        'frequency',
        'next_processing_date',
        'last_processed_at',
        'user_id',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
