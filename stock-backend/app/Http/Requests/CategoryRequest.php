<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{

   public function rules()
        {
            $rules = [
                'description' => 'nullable|string',
            ];
        
            if ($this->isMethod('post')) {
                $rules['name'] = 'required|string|max:255';
            } else {
                $rules['name'] = 'sometimes|string|max:255';
            }
        
            return $rules;
        }

}
