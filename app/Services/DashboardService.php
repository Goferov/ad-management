<?php

namespace App\Services;

use App\Repositories\Contracts\AdRepositoryInterface;
use Illuminate\Support\Collection;

class DashboardService
{
    public function __construct(
        private readonly AdRepositoryInterface $ads,
    ) {}

    public function getAdStats(): Collection
    {
        return $this->ads->getWithStats()
            ->map(function ($ad) {
                $impressions = (int) $ad->impressions_count;
                $clicks      = (int) $ad->clicks_count;

                $ad->ctr = $impressions > 0
                    ? round($clicks / $impressions * 100, 2)
                    : 0.0;

                return $ad;
            });
    }
}
