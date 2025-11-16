<?php

namespace App\Services;

use App\Enums\AdEventType;
use App\Repositories\Contracts\AdEventRepositoryInterface;
use App\Repositories\Contracts\AdRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TrackingService
{
    public function __construct(
        private readonly AdRepositoryInterface $ads,
        private readonly AdEventRepositoryInterface $events,
    ) {}

    public function trackImpression(int $adId): void
    {
        $this->createEvent($adId, AdEventType::Impression);
    }

    public function trackClick(int $adId): void
    {
        $this->createEvent($adId, AdEventType::Click);
    }

    private function createEvent(int $adId, AdEventType $type): void
    {
        $this->ensureAdExists($adId);

        $this->events->create($adId, $type);
    }

    private function ensureAdExists(int $adId): void
    {
        if (! $this->ads->find($adId)) {
            throw new ModelNotFoundException("Ad {$adId} not found");
        }
    }
}
