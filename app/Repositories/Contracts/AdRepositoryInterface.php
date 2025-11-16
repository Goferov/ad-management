<?php

namespace App\Repositories\Contracts;

use App\Models\Ad;
use Illuminate\Support\Collection;

interface AdRepositoryInterface
{
    public function create(array $data): Ad;

    public function find(int $id): ?Ad;

    public function getWithStats(): Collection;
}
