<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Services\ContactService;
use App\Http\Resources\ContactResource;
use App\Http\Requests\{ContactFilterRequest, ContactRequest};
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ContactController extends Controller
{
    public function __construct(private ContactService $contactService)
    {
    }

    public function index(ContactFilterRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();

        $contacts = $this->contactService->getFilteredAndSortedContacts(auth()->id(), $validated);

        return ContactResource::collection($contacts);
    }

    public function store(ContactRequest $request): ContactResource
    {
        return new ContactResource($this->contactService->createContact($request->validated()));
    }

    public function show(int $contact): ContactResource
    {
        return new ContactResource($this->contactService->getContactById($contact));
    }

    public function update(ContactRequest $request, int $contact): ContactResource
    {
        return new ContactResource($this->contactService->updateContact($contact, $request->validated()));
    }

    public function destroy($id): Response
    {
        $this->contactService->deleteContact($id);

        return response()->noContent();
    }
}
