<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WhyChooseUsCreateRequest extends FormRequest
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
            'icon' => ['sometimes', Rule::notIn(['empty']), 'max:50'],
            'title' => ['required', 'max:255'],
            'short_description' => ['required', 'max:500'],
            'status' => ['required', 'boolean']
        ];
    }


    public function messages()
    {
        return [
            'icon.not_in' => 'Please select an icon',
        ];
    }
}