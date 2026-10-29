<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/** Ubah kontak & alamat perusahaan (Admin > Kontak & Alamat). */
class CompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // sudah dijaga middleware "auth" pada route
    }

    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'max:20', function (string $attribute, mixed $value, \Closure $fail) {
                $digits = (string) preg_replace('/\D/', '', (string) $value);

                // Hanya angka, spasi, +, -, dan tanda kurung; 9-15 digit
                if (! preg_match('/^[0-9+\-\s()]+$/', (string) $value) || strlen($digits) < 9 || strlen($digits) > 15) {
                    $fail('Nomor telepon tidak valid. Contoh: 0811 3561 43 atau +62 811 3561 43.');
                }
            }],
            'email' => ['required', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'short_address' => ['required', 'string', 'max:100'],
            'maps_url' => ['required', 'url:https', 'max:500'],
            'maps_query' => ['required', 'string', 'max:200'],
        ];
    }

    public function attributes(): array
    {
        return [
            'phone' => 'nomor telepon',
            'email' => 'email',
            'address' => 'alamat lengkap',
            'short_address' => 'alamat singkat',
            'maps_url' => 'link Google Maps',
            'maps_query' => 'lokasi di peta',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':Attribute wajib diisi.',
            'max' => ':Attribute terlalu panjang (maksimal :max karakter).',
            'email.email' => 'Format email tidak valid.',
            'maps_url.url' => 'Link Google Maps harus berupa alamat web yang diawali https://',
        ];
    }
}