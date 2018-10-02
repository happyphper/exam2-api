<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserGroupRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'group_id' => 'required|exists:groups,id',
                    'users.*.name' => ['required', 'string', 'min:1', 'max:16',],
                    'users.*.email' => ['sometimes', 'string', 'email'],
                    'users.*.phone' => ['sometimes', 'regex:/1[3-9]\d{9}/'],
                    'users.*.student_id' => ['required',],
                ];
                break;
            case 'PUT':
                return [];
                break;
        }


    }
}
