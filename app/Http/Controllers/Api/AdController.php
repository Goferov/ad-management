<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdRequest;
use App\Services\AdService;
use Illuminate\Http\JsonResponse;

class AdController extends Controller
{
    public function __construct(
        private readonly AdService $adService,
    ) {}

    public function store(StoreAdRequest $request): JsonResponse
    {
        $ad = $this->adService->create($request->validated());

        return response()->json([
            'data' => $ad,
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $ad = $this->adService->getByIdOrFail($id);

        return response()->json([
            'data' => $ad,
        ]);
    }
}

