<?php

namespace App\Services;

use App\Models\Ad;
use App\Repositories\Contracts\AdRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DashboardService
{
    public function __construct(
        private readonly AdRepositoryInterface $ads,
    ) {}

    public function getAdStatsPaginated(int $perPage = 15): LengthAwarePaginator
    {
        $paginator = $this->ads->paginateWithStats($perPage);

        $paginator->getCollection()->transform(function (Ad $ad) {
            $impressions = (int) ($ad->impressions_count ?? 0);
            $clicks      = (int) ($ad->clicks_count ?? 0);

            $ad->ctr = $impressions > 0
                ? round($clicks / $impressions * 100, 2)
                : 0.0;

            return $ad;
        });

        return $paginator;
    }
}
