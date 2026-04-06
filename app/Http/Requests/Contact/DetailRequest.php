<?php
namespace App\Http\Requests\Contact;
use Illuminate\Foundation\Http\FormRequest;
class DetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'extra' => 'required|min:2|max:500',
            'contact_general_id' => 'required',
        ];
    }
}