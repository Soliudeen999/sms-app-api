<?php

declare(strict_types=1);

namespace App\Http\Resources\Contact;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->whenHas('id'),
            'name' => $this->whenHas('name'),
            'email' => $this->whenHas('email'),
            'phone_number' => $this->whenHas('phone_number'),
            'country_code' => $this->whenHas('country_code'),
            'note' => $this->whenHas('note'),
            'created_at' => $this->whenHas('created_at'),
            'updated_at' => $this->whenHas('updated_at'),

            'owner' => new UserResource($this->whenLoaded('owner')),
        ];
    }
}
