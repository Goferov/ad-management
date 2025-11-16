<?php

namespace App\Services;

use App\Models\Ad;
use App\Repositories\Contracts\AdRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdService
{
    public function __construct(
        private readonly AdRepositoryInterface $ads,
    ) {}

    public function create(array $data): Ad
    {
        $image = $data['image'] ?? null;

        if (! $image instanceof UploadedFile) {
            throw new \InvalidArgumentException('Image file is required');
        }

        $imageUrl = $this->storeAdImage($image);

        unset($data['image']);
        $data['image_url'] = $imageUrl;

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

    private function storeAdImage(UploadedFile $image): string
    {
        $path = $image->store('ads', 'public');

        return Storage::disk('public')->url($path);
    }
}
