<?php

namespace App\Repositories;

use App\Models\Contact;
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
}
