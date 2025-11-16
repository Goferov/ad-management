<?php

namespace App\Models;

use App\Enums\AdEventType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdEvent extends Model
{
    protected $fillable = [
        'ad_id',
        'type',
    ];

    protected $casts = [
        'type' => AdEventType::class,
    ];

    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }
}
