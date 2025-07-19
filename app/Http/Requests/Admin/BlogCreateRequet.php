<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BlogCreateRequet extends FormRequest
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
            'title' => ['required', 'max:255', 'unique:blogs,title'],
            'image' => ['required', 'image'],
            'category' => ['required'],
            'description' => ['required'],
            'seo_title' => ['max:255'],
            'seo_description' => ['max:1000'],
            'status' => ['required', 'boolean'],
        ];
    }
}