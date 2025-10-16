<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'status'    => $this->status,
            'total'     => $this->total,
            'items'     => OrderItemResource::collection($this->whenLoaded('items')),
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,
        ];
    }
}
