<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\ArrayUniqueInDatabaseRule;
use App\Rules\ArrayUserUniqueRule;
use Illuminate\Foundation\Http\FormRequest;

class BulkUserRequest extends FormRequest
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
            'classroom_id'           => ['required', 'exists:classrooms,id'],
            'users' => ['required', 'array', new ArrayUserUniqueRule()],
            'users.*.name'       => ['required', 'string', 'min:1', 'max:16',],
            'users.*.email'      => ['sometimes', 'string', 'email', new ArrayUniqueInDatabaseRule('email', new User())],
            'users.*.phone'      => ['sometimes', 'regex:/1[3-9]\d{9}/', new ArrayUniqueInDatabaseRule('phone', new User())],
            'users.*.student_id' => ['required', new ArrayUniqueInDatabaseRule('student_id', new User())],
            'users.*.password'   => ['sometimes', 'regex:/.{6,}/'],
        ];
    }
}
