<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class AdStatsController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService,
    ) {}

    public function index(): JsonResponse
    {
        $ads = $this->dashboardService->getAdStats();

        return response()->json([
            'data' => $ads,
        ]);
    }
}
