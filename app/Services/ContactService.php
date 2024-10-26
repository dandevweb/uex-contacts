<?php

namespace App\Services;

use App\Models\Contact;
use App\Repositories\ContactRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactService
{
    protected ContactRepository $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function getFilteredAndSortedContacts(int $userId, array $validatedData): LengthAwarePaginator
    {
        return $this->contactRepository->getFilteredAndSortedByUser($userId, $validatedData);
    }

    public function createContact(array $data): Contact
    {
        $data['user_id'] = auth()->id();
        return $this->contactRepository->create($data);
    }
}
