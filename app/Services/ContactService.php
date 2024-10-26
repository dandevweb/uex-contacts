<?php

namespace App\Services;

use App\Models\{Contact, User};
use App\Repositories\ContactRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactService
{
    public function __construct(private ContactRepository $contactRepository)
    {
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

    public function getContactById(int $id): Contact
    {
        /** @var User $user */
        $user = auth()->user();

        return $this->contactRepository->findById($id, $user);
    }

    public function updateContact(int $id, array $data): Contact
    {
        /** @var User $user */
        $user = auth()->user();

        return $this->contactRepository->update($id, $user, $data);
    }

    public function deleteContact($id): void
    {
        /** @var User $user */
        $user = auth()->user();

        $this->contactRepository->delete($id, $user);
    }
}
