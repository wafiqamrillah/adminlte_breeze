<?php

namespace App\Http\Requests\System\Menu;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreMenuRequest extends FormRequest
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
        return [
            'parent_id' => 'parent menu',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:100',
            'parent_id' => 'nullable|integer|exists:menus,id',
            'type' => 'required|string|in:menu,header,navbar',
            'link' => ['exclude_if:link_type,url', 'required', 'string', 'min:3', 'max:100', new \App\Rules\RouteExist],
            'link_type' => 'required|string|in:url,route',
            'icon_class' => 'required|string|min:3|max:100',
            'is_active' => 'required|boolean',
            'use_translation' => 'required|boolean',
            'order' => 'nullable|integer|min:0'
        ];
    }
}
