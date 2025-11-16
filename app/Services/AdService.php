<?php

namespace App\Services;

use App\Models\Ad;
use App\Repositories\Contracts\AdRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdService
{
    public function __construct(
        private readonly AdRepositoryInterface $ads,
    ) {}

    public function create(array $data): Ad
    {
        return $this->ads->create($data);
    }

    public function getByIdOrFail(int $id): Ad
    {
        $ad = $this->ads->find($id);

        if (! $ad) {
            throw new ModelNotFoundException("Ad {$id} not found");
        }

        return $ad;
    }
}
