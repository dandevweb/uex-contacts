<?php

namespace App\Http\Controllers;

use App\Services\ContactService;
use App\Http\Resources\ContactResource;
use App\Http\Requests\{ContactFilterRequest, ContactRequest};
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ContactController extends Controller
{
    protected ContactService $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
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
}
