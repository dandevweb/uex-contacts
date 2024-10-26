<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => 'nullable|string|max:255',
            'cpf'        => 'nullable|string|size:14,cpf',
            'sort_by'    => 'nullable|string|in:name,city,state',
            'sort_order' => 'nullable|string|in:asc,desc',
            'per_page'   => 'nullable|integer|min:1',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Configure default values if they are not provided
        $this->merge([
            'sort_by'    => $this->input('sort_by', 'name'),
            'sort_order' => $this->input('sort_order', 'asc'),
            'per_page'   => $this->input('per_page', 10),
        ]);
    }
}
