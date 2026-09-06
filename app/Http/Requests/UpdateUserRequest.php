<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('ADMIN') ?? false;
    }

    public function rules(): array
    {
        return ['name' => ['required', 'string', 'max:100'], 'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->route('user'))->withoutTrashed()], 'role' => ['required', Rule::in(['ADMIN', 'CASHIER', 'KITCHEN', 'OWNER'])], 'password' => ['nullable', 'confirmed', Password::min(8)], 'is_active' => ['nullable', 'boolean']];
    }
}
