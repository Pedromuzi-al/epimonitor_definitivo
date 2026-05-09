<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cpf',
        'age',
        'phone',
        'neighborhood',
        'zip_code',
        'street',
        'house_number',
        'housing_type',
        'address_complement',
        'city',
        'state',
    ];

    public function getCpfAttribute($value): string
    {
        $digits = preg_replace('/\D/', '', (string) $value);

        if (strlen($digits) !== 11) {
            return (string) $value;
        }

        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $digits);
    }

    public function setCpfAttribute($value): void
    {
        $this->attributes['cpf'] = preg_replace('/\D/', '', (string) $value);
    }

    public function getPhoneAttribute($value): string
    {
        $digits = preg_replace('/\D/', '', (string) $value);

        if (strlen($digits) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $digits);
        }

        if (strlen($digits) === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $digits);
        }

        return (string) $value;
    }

    public function setPhoneAttribute($value): void
    {
        $this->attributes['phone'] = preg_replace('/\D/', '', (string) $value);
    }

    public function getZipCodeAttribute($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $digits = preg_replace('/\D/', '', (string) $value);

        if (strlen($digits) !== 8) {
            return (string) $value;
        }

        return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $digits);
    }

    public function setZipCodeAttribute($value): void
    {
        $this->attributes['zip_code'] = preg_replace('/\D/', '', (string) $value);
    }

    public function diagnoses()
    {
        return $this->hasMany(Diagnosis::class);
    }
}
