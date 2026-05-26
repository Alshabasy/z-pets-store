<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('product');
        
        return [
            'name'              => 'required|string|max:255',
            'slug'              => [
                'required',
                'string',
                \Illuminate\Validation\Rule::unique('products', 'slug')
                    ->ignore($this->route('id')),
            ],
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
