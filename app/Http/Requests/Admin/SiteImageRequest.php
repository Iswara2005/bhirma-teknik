<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\ValidatesImageUpload;
use Illuminate\Foundation\Http\FormRequest;

class SiteImageRequest extends FormRequest
{
    use ValidatesImageUpload;

    public function authorize(): bool
    {
        return true; // sudah dijaga middleware "auth" pada route
    }

    /** Setiap slot punya wadah error sendiri, supaya pesan muncul di kartu yang benar. */
    protected function prepareForValidation(): void
    {
        $this->errorBag = 'slot_'.$this->route('key');
    }

    public function rules(): array
    {
        return ['image' => $this->imageRules(required: true)];
    }

    public function messages(): array
    {
        return $this->imageMessages();
    }
}
