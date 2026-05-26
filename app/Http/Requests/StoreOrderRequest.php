<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'notes'          => 'nullable|string|max:1000',
        ];
    }
}
