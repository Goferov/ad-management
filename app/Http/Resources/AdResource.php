<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'text'         => $this->text,
            'image_url'    => $this->image_url,
            'target_url'   => $this->target_url,
            'impressions'  => (int) ($this->impressions_count ?? 0),
            'clicks'       => (int) ($this->clicks_count ?? 0),
            'ctr'          => $this->ctr ?? 0.0,
            'created_at'   => optional($this->created_at)->toIso8601String(),
            'updated_at'   => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
