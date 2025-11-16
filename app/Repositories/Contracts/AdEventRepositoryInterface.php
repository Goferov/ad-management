<?php

namespace App\Repositories\Contracts;

use App\Enums\AdEventType;
use App\Models\AdEvent;

interface AdEventRepositoryInterface
{
    public function create(int $adId, AdEventType $type): AdEvent;
}
