<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdResource;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdStatsController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage   = $request->integer('per_page', 10);
        $paginator = $this->dashboardService->getAdStatsPaginated($perPage);

        return AdResource::collection($paginator)->response();

    }
}
