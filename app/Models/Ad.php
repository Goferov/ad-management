<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ad extends Model
{
    protected $fillable = [
        'title',
        'text',
        'image_url',
        'target_url',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(AdEvent::class);
    }
}
