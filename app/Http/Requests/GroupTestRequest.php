<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GroupTestRequest extends FormRequest
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
            'test_id' => [
                'required',
                'exists:tests,id',
                Rule::unique('group_tests')->where('test_id', $this->test_id)->where('group_id', $this->group_id)
            ],
            'group_id' => 'required|exists:groups,id'
        ];
    }
}
