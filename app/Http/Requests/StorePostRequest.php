<?php

namespace App\Http\Requests;

use App\Rules\IntegerArray;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => ['string', 'required'],
            'body' => ['string', 'required'],
            'user_ids' => [
                'array', 
                'required', 
                new IntegerArray()]
        ];
    }

    public function messages()
    {
        return [
            'title.required' => "Where is your title?",
            'body.string' => "Use a string jare"
        ];
    }
}
