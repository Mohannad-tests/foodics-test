<?php

namespace FoodicsTest\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateNewOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'products'            => ['required', 'array'],
            'products.*.id'       => ['required', 'integer', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
