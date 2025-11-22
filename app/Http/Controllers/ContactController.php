<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contact\StoreContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Http\Resources\Contact\ContactCollection;
use App\Http\Resources\Contact\ContactResource;
use App\Models\Contact;
use App\Services\Contact\ContactService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class ContactController extends Controller
{
    public function __construct(
        private ContactService $contactService
    ) {}

    public function index()
    {
        $contacts = $this->contactService->getAll(auth()->user());
        return Response::success(
            new ContactCollection($contacts)
        );
    }

    public function store(StoreContactRequest $request)
    {
        Gate::authorize('create', Contact::class);

        return Response::success(
            new ContactResource($this->contactService->store(auth()->user(), $request->validated())),
            'Contact created successfully',
            201
        );
    }

    public function show(Contact $contact)
    {
        Gate::authorize('view', $contact);

        return Response::success(
            new ContactResource($this->contactService->getSingle($contact)),
            'Contact retrieved successfully',
        );
    }

    public function update(UpdateContactRequest $request, Contact $contact)
    {
        Gate::authorize('update', $contact);

        return Response::success(
            new ContactResource($this->contactService->update($contact, $request->validated())),
            'Contact updated successfully',
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        Gate::authorize('delete', $contact);

        $this->contactService->delete($contact);
        return Response::noContent();
    }
}
