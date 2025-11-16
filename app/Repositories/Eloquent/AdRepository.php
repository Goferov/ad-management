<?php

namespace App\Repositories\Eloquent;

use App\Models\Ad;
use App\Repositories\Contracts\AdRepositoryInterface;
use Illuminate\Support\Collection;

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

    public function getWithStats(): Collection
    {
        return $this->ad->withCount([
            'events as impressions_count' => function ($q) {
                $q->where('type', 'impression');
            },
            'events as clicks_count' => function ($q) {
                $q->where('type', 'click');
            },
        ])->get();
    }
}
