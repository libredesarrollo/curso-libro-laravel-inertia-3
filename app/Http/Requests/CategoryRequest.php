<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{

    function prepareForValidation()
    {
        if(str($this->slug)->trim() == ''){
            $this->merge(['slug' => str($this->title)->slug()]);
        }
        return parent::prepareForValidation();
    }

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "title" => 'required|min:3|max:255',
            "slug" => 'required|min:3|max:255|unique:categories,slug,'.$this->route("category")?->id
        ];
    }
}
