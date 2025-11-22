<?php

declare(strict_types=1);

namespace App\Services\Contact;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ContactService
{

    private Contact $contactModel;

    public function __construct()
    {
        $this->contactModel = new Contact;
    }

    public function getAll(?User $user): LengthAwarePaginator
    {
        return $this->contactModel->query()
            ->when($user, fn($query) => $query->where('user_id', $user->id))
            ->filter()
            ->search()
            ->sort()
            ->paginate();
    }

    public function getSingle(Contact|int $contact): Contact
    {
        if ($contact instanceof Contact) {
            return $contact->load(['owner:id,name']);
        }

        return $this->contactModel->query()
            ->with(['owner:id,name'])
            ->findOrFail($contact);
    }

    public function store(User $user, array $data): Contact
    {
        $data['user_id'] ??= $user->id;
        return $this->contactModel->create($data);
    }

    public function update(Contact $contact, array $data): Contact
    {
        $contact->update($data);
        return $contact;
    }

    public function delete(Contact $contact): void
    {
        $contact->delete();
    }
}
