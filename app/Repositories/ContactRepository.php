<?php

namespace App\Repositories;

use App\Models\{Contact, User};
use Illuminate\Pagination\LengthAwarePaginator;

class ContactRepository
{
    public function getFilteredAndSortedByUser(int $userId, array $data): LengthAwarePaginator
    {
        $name = $data['name'] ?? null;
        $cpf  = $data['cpf'] ?? null;

        return Contact::whereUserId($userId)
            ->when($name, fn ($query) => $query->where('name', 'like', "%$name%"))
            ->when($cpf, fn ($query) => $query->where('cpf', $cpf))
            ->orderBy($data['sort_by'], $data['sort_order'])
            ->paginate($data['per_page']);
    }

    public function create(array $data): Contact
    {
        return Contact::create($data);
    }

    public function findById(int $id, User $user): Contact
    {
        return $user->contacts()->whereId($id)->firstOrFail();
    }

    public function update(int $id, User $user, array $data): Contact
    {
        $contact = $this->findById($id, $user);

        $contact->update($data);

        return $contact->fresh();
    }

    public function delete(int $id, User $user): void
    {
        $contact = $this->findById($id, $user);

        $contact->delete();
    }
}
