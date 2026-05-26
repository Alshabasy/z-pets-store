<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'slug'              => 'required|string|unique:products,slug',
            'category_id'       => 'required|exists:categories,id',
            'description'       => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price'             => 'required|numeric|min:0',
            'sale_price'        => 'nullable|numeric|lt:price',
            'in_stock'          => 'nullable|boolean',
            'is_featured'       => 'nullable|boolean',
            'is_active'         => 'nullable|boolean',
            'images'            => 'nullable|array',
            'images.*'          => 'image|mimes:jpeg,jpg,png,webp|max:2048',
            'tags'              => 'nullable|array',
            'tags.*'            => 'exists:tags,id',
        ];
    }
}
