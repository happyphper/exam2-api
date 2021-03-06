<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
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
                    'name' => 'required|string|min:1|max:16',
                    'email' => 'nullable|email|max:30|unique:users,email',
                    'phone' => 'required|regex:/1[3-9]\d{9}/|unique:users,phone',
                ];
                break;
            case 'PUT':
                return [
                    'name' => 'required|string|min:1|max:16|unique:categories,name,' . $this->teacher->id,
                    'email' => 'nullable|email|max:30|unique:users,email,' . $this->teacher->id,
                    'phone' => 'required|regex:/1[3-9]\d{9}/|unique:users,phone,' . $this->teacher->id,
                ];
                break;
        }
    }
}
