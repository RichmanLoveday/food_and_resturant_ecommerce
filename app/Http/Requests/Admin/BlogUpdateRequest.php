<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BlogUpdateRequest extends FormRequest
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

        //? access id from the request
        $id = $this->blog;
        return [
            'title' => ['required', 'max:255', 'unique:blogs,title,' . $id],
            'image' => ['nullable', 'image'],
            'category' => ['required'],
            'description' => ['required'],
            'seo_title' => ['max:255'],
            'seo_description' => ['max:255'],
            'status' => ['required', 'boolean'],
        ];
    }
}
