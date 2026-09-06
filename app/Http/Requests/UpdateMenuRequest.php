<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('ADMIN') ?? false;
    }

    public function rules(): array
    {
        return ['category_id' => ['required', 'exists:categories,id'], 'name' => ['required', 'string', 'max:120'], 'description' => ['nullable', 'string', 'max:500'], 'price' => ['required', 'numeric', 'min:0', 'max:9999999999999'], 'estimated_prep_minutes' => ['required', 'integer', 'min:1', 'max:120'], 'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], 'is_available' => ['nullable', 'boolean'], 'is_active' => ['nullable', 'boolean']];
    }
}
