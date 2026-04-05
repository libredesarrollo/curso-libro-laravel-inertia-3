<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function prepareForValidation()
    {
        if (str($this->slug)->trim() == '') {
            $this->merge(['slug' => str($this->title)->slug()]);
        }

        if ($this->has('category_id')) {
            $this->merge(['category_id' => (int) $this->category_id]);
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
            'slug' => 'required|min:3|max:255|unique:posts,slug,'.$this->route('post')?->id,
            'description' => 'required|min:3',
            'text' => 'nullable',
            'posted' => 'required|in:yes,not',
            'type' => 'required|in:advert,post,course,movie',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif|max:10240'
        ];
    }
}
