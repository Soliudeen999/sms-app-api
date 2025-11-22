<?php

declare(strict_types=1);

namespace App\Services\Message;

use App\Enums\Message\MessageType;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MessageService
{

    private Message $messageModel;

    public function __construct()
    {
        $this->messageModel = new Message;
    }

    public function getAllMessage(?User $user): LengthAwarePaginator
    {
        return $this->messageModel->query()
            ->when($user, fn($query) => $query->where('user_id', $user->id))
            ->filter()
            ->search()
            ->sort()
            ->paginate();
    }

    public function getSingleMessage(Message|int $message): Message
    {
        if ($message instanceof Message) {
            return $message->load(['messages', 'owner']);
        }

        return $this->messageModel->query()
            ->with(['messages', 'owner'])
            ->findOrFail($message);
    }

    public function storeMessage(User $user, array $data): Message
    {
        $data['user_id'] ??= auth()->id();
        $data['provider'] = setting('active_provider', '2frocoms');

        $message = DB::transaction(function () use ($data) {
            $message = $this->messageModel->create($data);
        });

        if ($message->type->is(MessageType::INSTANT)) {
            $this->sendProcessMessage();
        }

        return $message;
    }

    private function sendProcessMessage() {}
}
