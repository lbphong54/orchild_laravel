<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product.name' => 'required|string',
            'product.price'=> 'required|numeric',
            'product.description'=> 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'product.name.required' => 'Product name is required',
            'product.price.required' => 'Product price is required',
            'product.price.numeric'=> 'Product price must be a number',
            'product.description.required' => 'Product description is required',
        ];
    }
}
