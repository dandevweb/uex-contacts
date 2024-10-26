<?php

namespace App\Http\Controllers;

use Illuminate\Support\Fluent;
use App\Services\{ViaCepService};
use App\Http\Resources\AddressResource;
use Illuminate\Http\{Request};
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AddressController extends Controller
{
    public function __construct(private ViaCepService $viaCepService)
    {
    }

    public function getAddressByZipCode(Request $request): AddressResource
    {
        $request->validate([
            'zip_code' => ['required', 'formato_cep'],
        ]);

        $address = $this->viaCepService->getAddressByZipCode($request->zip_code);

        $addressObject = new Fluent($address);

        return new AddressResource($addressObject);
    }

    public function suggestAddresses(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'state'  => ['required', 'string', 'max:2', 'uf'],
            'city'   => ['required', 'string', 'max:255'],
            'street' => ['required', 'string', 'max:255'],
        ]);

        $suggestions = $this->viaCepService->suggestAddresses($request->state, $request->city, $request->street);

        $suggestions = array_map(fn ($suggestion) => new Fluent($suggestion), $suggestions);

        return AddressResource::collection($suggestions);
    }
}
