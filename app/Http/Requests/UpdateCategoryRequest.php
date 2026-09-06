<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('ADMIN') ?? false;
    }

    public function rules(): array
    {
        return ['name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($this->route('category'))->withoutTrashed()], 'sort_order' => ['required', 'integer', 'min:0', 'max:999'], 'is_active' => ['nullable', 'boolean']];
    }
}
