<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\Models\User;

trait BelongsToUser
{
    protected static function bootBelongsToUser()
    {
        if (auth()->check()) {
            static::addGlobalScope('user_id', function (Builder $builder) {
                $builder->where($builder->getQuery()->from . '.user_id', auth()->id());
            });

            static::creating(function ($model) {
                if (!$model->user_id) {
                    $model->user_id = auth()->id();
                }
            });
        }
    }
}
