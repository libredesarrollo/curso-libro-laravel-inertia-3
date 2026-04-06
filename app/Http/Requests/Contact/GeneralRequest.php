<?php

namespace App\Http\Requests\Contact;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GeneralRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
   public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'subject' => 'required|min:2|max:255',
            'type' => 'required',
            'message' => 'required|min:2',
        ];
    }
}
