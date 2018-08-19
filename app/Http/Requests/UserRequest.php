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
                    'email' => 'required|email|max:30|unique:users,email',
                    'group_id' => 'sometimes|exists:groups,id'
                ];
                break;
            case 'PUT':
                return [
                    'name' => 'required|string|min:1|max:191|unique:categories,name,' . $this->user->id,
                    'email' => 'required|email|max:30|unique:users,email,' . $this->user->id,
                    'group_id' => 'sometimes|exists:groups,id'
                ];
                break;
        }
    }
}
