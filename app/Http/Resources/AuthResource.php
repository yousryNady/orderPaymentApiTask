<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class AuthResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'success' => true,
            'token' => $this['token'],
            'user' => new UserResource($this['user']),
        ];
    }
}
