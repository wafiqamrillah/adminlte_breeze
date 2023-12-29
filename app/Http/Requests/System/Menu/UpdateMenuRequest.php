<?php

namespace App\Http\Requests\System\Menu;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateMenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        $attributes = (new StoreMenuRequest)->attributes();
        $attributes['id'] = 'ID';
        
        return $attributes;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = (new StoreMenuRequest)->rules();
        $rules['id'] = 'required|integer|exists:menus,id';

        return $rules;
    }
}
