<?php

namespace App\Http\Resources\MediaStorage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaStorageCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            /*
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'url' => $this->url,
            'path' => $this->path,
            */
            'url' => $this->url,
        ];
    }
}
