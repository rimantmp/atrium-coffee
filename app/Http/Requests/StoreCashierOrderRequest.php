<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCashierOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('CASHIER', 'ADMIN') ?? false;
    }

    public function rules(): array
    {
        return ['customer_name' => ['required', 'string', 'max:100'], 'customer_phone' => ['nullable', 'string', 'max:25'], 'table_number' => ['nullable', 'string', 'max:30'], 'order_type' => ['required', 'in:DINE_IN,TAKEAWAY'], 'method' => ['required', 'in:CASH,QRIS'], 'notes' => ['nullable', 'string', 'max:500'], 'items' => ['required', 'array', 'min:1'], 'items.*.menu_id' => ['required', 'integer', 'distinct'], 'items.*.quantity' => ['required', 'integer', 'min:1', 'max:20'], 'items.*.notes' => ['nullable', 'string', 'max:500']];
    }
}
