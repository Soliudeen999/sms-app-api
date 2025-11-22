<?php

declare(strict_types=1);

namespace App\Http\Resources\Campaign;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->whenHas('id'),
            'user_id' => $this->whenHas('user_id'),
            'title' => $this->whenHas('title'),
            'body' => $this->whenHas('body'),
            'recipient_type' => $this->whenHas('recipient_type'),
            'type' => $this->whenHas('type'),
            'status' => $this->whenHas('status'),
            'scheduled_at' => $this->whenHas('scheduled_at'),
            'recurrence_config' => $this->whenHas('recurrence_config'),
            'recipients' => $this->whenHas('recipients'),
            'extra_recipient_numbers' => $this->whenHas('extra_recipient_numbers'),
            'last_processed_at' => $this->whenHas('last_processed_at'),
            'created_at' => $this->whenHas('created_at'),
            'updated_at' => $this->whenHas('updated_at'),

            'owner' => new UserResource($this->whenLoaded('owner')),
        ];
    }
}
