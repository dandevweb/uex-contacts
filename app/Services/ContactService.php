<?php

namespace App\Services;

use App\Models\{Contact, User};
use App\Repositories\ContactRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactService
{
    private ?User $user = null;

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
        return $this->contactRepository->findById($id, $this->getUser());
    }

    public function updateContact(int $id, array $data): Contact
    {
        return $this->contactRepository->update($id, $this->getUser(), $data);
    }

    public function deleteContact($id): void
    {
        $this->contactRepository->delete($id, $this->getUser());
    }

    private function getUser(): User
    {
        if (!$this->user) {
            $this->user = auth()->user();
        }

        return $this->user;
    }
}
