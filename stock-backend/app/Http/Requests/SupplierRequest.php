<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    public function rules(): array
    {

        if ($this->isMethod('post')) {
            $rules = [
                'name'   => 'required|string|max:255',
                'email'   => 'required|email|max:255',
                'phone'   => 'required|string|max:20',
                'address' => 'required|string|max:500',
        ];
        } else if($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules = [
                'name'   => 'sometimes|string|max:255',
                'email'   => 'sometimes|email|max:255',
                'phone'   => 'sometimes|string|max:20',
                'address' => 'sometimes|string|max:500',
        ];
        }

        return $rules;
    }
}
