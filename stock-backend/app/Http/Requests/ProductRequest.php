<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ];

        // 🔁 Si c’est une mise à jour (PATCH ou PUT), on rend les champs optionnels
        if ($this->isMethod('PATCH') || $this->isMethod('PUT')) {
            foreach ($rules as $key => &$rule) {
                $rule = str_replace('required', 'sometimes', $rule);
            }
        }

        return $rules;
    }
}
