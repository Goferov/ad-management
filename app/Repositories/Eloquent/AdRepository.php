<?php

namespace App\Repositories\Eloquent;

use App\Enums\AdEventType;
use App\Models\Ad;
use App\Repositories\Contracts\AdRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdRepository implements AdRepositoryInterface
{

    public function __construct(private Ad $ad)
    {
    }

    public function create(array $data): Ad
    {
        return $this->ad->create($data);
    }

    public function find(int $id): ?Ad
    {
        return $this->ad->find($id);
    }

    public function paginateWithStats(int $perPage = 15): LengthAwarePaginator
    {
        return $this->ad->withCount([
            'events as impressions_count' => fn ($q) => $q->where('type', AdEventType::Impression),
            'events as clicks_count'      => fn ($q) => $q->where('type', AdEventType::Click),
        ])->orderByDesc('created_at')
            ->paginate($perPage);
    }
}
