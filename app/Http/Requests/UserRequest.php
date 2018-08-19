<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
                    'name' => 'required|string|min:1|max:191|unique:users',
                    'email' => 'nullable|email|max:30|unique:users,email',
                    'phone' => 'nullable|regex:/1[3-9]\d{9}/|unique:users,phone',
                    'student_id' => 'nullable|string|min:1|max:191|unique:users',
                    'group_id' => 'sometimes|exists:groups,id'
                ];
                break;
            case 'PUT':
                return [
                    'name' => 'required|string|min:1|max:191|unique:categories,name,' . $this->user->id,
                    'email' => 'required|email|max:30|unique:users,email,' . $this->user->id,
                    'phone' => 'nullable|regex:1[3-9]\d{9}|unique:users,phone,' . $this->user->id,
                    'student_id' => 'required|string|min:1|max:191|unique:users,student_id,' . $this->student_id,
                    'group_id' => 'sometimes|exists:groups,id'
                ];
                break;
        }
    }
}
