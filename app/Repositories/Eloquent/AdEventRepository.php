<?php

namespace App\Repositories\Eloquent;

use App\Enums\AdEventType;
use App\Models\AdEvent;
use App\Repositories\Contracts\AdEventRepositoryInterface;

class AdEventRepository implements AdEventRepositoryInterface
{

    public function __construct(private AdEvent $adEvent)
    {
    }

    public function create(int $adId, AdEventType $type): AdEvent
    {
        return $this->adEvent->create([
            'ad_id' => $adId,
            'type'  => $type,
        ]);
    }
}
