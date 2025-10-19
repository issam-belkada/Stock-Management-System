<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // handled by middleware or policy
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku,' . $this->id,
            'price' => 'required|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'low_threshold' => 'nullable|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ];
    }
}
