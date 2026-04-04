<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
{
    public function prepareForValidation()
    {
        if (str($this->slug)->trim() == '') {
            $this->merge(['slug' => str($this->title)->slug()]);
        }

        return parent::prepareForValidation();
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|min:3|max:255',
            'slug' => 'required|min:3|max:255|unique:tags,slug,'.$this->route('tag')?->id,
        ];
    }
}
