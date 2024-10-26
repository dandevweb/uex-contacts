<?php

namespace App\Models;

use App\Services\GoogleMapsService;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\CoordinatesNotFoundException;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;

    protected $casts = [
        'number' => 'string',
    ];

    protected $hidden = ['user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function completeAddress(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->address}, {$this->number} - {$this->neighborhood}, {$this->city}, {$this->state}, {$this->zip_code}",
        );
    }

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function ($contact) {
            if ($contact->address && !$contact->latitude && !$contact->longitude) {
                $googleMapsService = app(GoogleMapsService::class);
                $coordinates       = $googleMapsService->getCoordinatesByAddress($contact->completeAddress);

                if (!$coordinates) {
                    throw new CoordinatesNotFoundException(__('Coordinates not found for address.'));
                }

                $contact->latitude  = $coordinates['lat'];
                $contact->longitude = $coordinates['lng'];
            }
        });
    }

}
