<?php

namespace App\Repositories\Contracts;

use App\Models\AdEvent;

interface AdEventRepositoryInterface
{
    public function create(int $adId, string $type): AdEvent;
}
