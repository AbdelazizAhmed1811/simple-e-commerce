<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Only allow users with the role of 'admin' to update products
        return $this->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'img' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_type' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'in_stock' => 'sometimes|integer|min:0',
            'category' => 'sometimes|string|max:255',
        ];
    }
}
