<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskUpdateRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required_without:title,description|integer|exists:users,id',
            'title' => 'required_without:user_id,description|string|max:255',
            'description' => 'required_without:user_id,title|string|max:65535',
        ];
    }
}
