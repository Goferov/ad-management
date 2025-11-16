<?php

namespace App\Repositories\Eloquent;

use App\Models\AdEvent;
use App\Repositories\Contracts\AdEventRepositoryInterface;
use Faker\Provider\Address;

class AdEventRepository implements AdEventRepositoryInterface
{

    public function __construct(private AdEvent $adEvent)
    {
    }

    public function create(int $adId, string $type): AdEvent
    {
        return $this->adEvent->create([
            'ad_id' => $adId,
            'type'  => $type,
        ]);
    }
}
