<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'address'      => $this->logradouro ?? '',
            'neighborhood' => $this->bairro ?? '',
            'city'         => $this->localidade ?? '',
            'state'        => $this->uf ?? '',
            'zip_code'     => $this->cep ?? '',
        ];
    }
}
