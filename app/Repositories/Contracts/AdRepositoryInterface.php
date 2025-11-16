<?php

namespace App\Repositories\Contracts;

use App\Models\Ad;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AdRepositoryInterface
{
    public function create(array $data): Ad;

    public function find(int $id): ?Ad;

    public function paginateWithStats(int $perPage = 15): LengthAwarePaginator;
}
