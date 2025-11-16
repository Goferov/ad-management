<?php

namespace App\Http\Controllers\Api;

use App\Enums\AdEventType;
use App\Http\Controllers\Controller;
use App\Http\Requests\TrackEventRequest;
use App\Services\TrackingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TrackingController extends Controller
{
    public function __construct(
        private readonly TrackingService $trackingService,
    ) {}

    public function store(TrackEventRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $type = AdEventType::from($validated['type']);

            match ($type) {
                AdEventType::Impression => $this->trackingService->trackImpression($validated['ad_id']),
                AdEventType::Click      => $this->trackingService->trackClick($validated['ad_id']),
            };

            return response()->json(['status' => 'ok']);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Ad not found',
            ], 404);
        }
    }
}
