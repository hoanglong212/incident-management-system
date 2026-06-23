<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncidentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'code' => $this->code,

        'title' => $this->title,
        'description' => $this->description,
        'priority' => $this->priority,
        'status' => $this->status,

        'address' => $this->address,
        'ward' => $this->ward,
        'district' => $this->district,
        'city' => $this->city,

        'latitude' => $this->latitude,
        'longitude' => $this->longitude,

        'category_id' => $this->category_id,
        'reporter_id' => $this->reporter_id,
        'assigned_to' => $this->assigned_to,

        'occurred_at' => $this->occurred_at,
        'created_at' => $this->created_at,
    ];
}
}
