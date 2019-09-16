<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class BaseModel extends Model
{
    use Cachable;

    static public function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            $query->orderBy('created_at', 'desc');
        });
    }
}
