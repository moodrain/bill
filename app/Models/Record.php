<?php

namespace App\Models;

class Record extends Model
{
    public function app()
    {
        return $this->belongsTo(App::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeIn($query)
    {
        return $query->where('number', '>', 0);
    }

    public function scopeOut($query)
    {
        return $query->where('number', '<', 0);
    }
}
