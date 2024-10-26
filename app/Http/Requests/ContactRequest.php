<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'cpf'          => ['required', 'string', 'size:14,cpf', Rule::unique('contacts')->ignore($this->route('contact'))->where('user_id', auth()->id())],
            'phone'        => ['required', 'string', 'celular_com_ddd'],
            'address'      => ['required', 'string', 'max:255'],
            'number'       => ['required', 'string', 'max:100'],
            'complement'   => ['nullable', 'string', 'max:255'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'city'         => ['required', 'string', 'max:255'],
            'state'        => ['required', 'string', 'max:2', 'uf'],
            'zip_code'     => ['required', 'string', 'max:9', 'formato_cep'],
            'latitude'     => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'    => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }
}
