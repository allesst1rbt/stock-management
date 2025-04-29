<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetProductsRequest extends FormRequest
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
            'name' => 'nullable|string|max:255|regex:/^[\w\s\-\.,]+$/',
            'category' => 'nullable|string|max:255|regex:/^[\w\s\-\.,]+$/',
            'price' => 'nullable|numeric|min:0|decimal:0,2',
            'per_page' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
            'sort_by' => 'nullable|in:name,created_at',
            'sort_order' => 'nullable|in:asc,desc',
        ];
    }
}
