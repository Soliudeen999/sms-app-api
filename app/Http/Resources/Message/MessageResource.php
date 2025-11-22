<?php

declare(strict_types=1);

namespace App\Http\Resources\Message;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->whenHas('id'),
            'user_id' => $this->whenHas('user_id'),
            'contact_id' => $this->whenHas('contact_id'),
            'title' => $this->whenHas('title'),
            'body' => $this->whenHas('body'),
            'phone_number' => $this->whenHas('phone_number'),
            'type' => $this->whenHas('type'),
            'status' => $this->whenHas('status'),
            'status_changed_at' => $this->whenHas('status_changed_at'),
            'created_at' => $this->whenHas('created_at'),
            'updated_at' => $this->whenHas('updated_at'),
        ];
    }
}
