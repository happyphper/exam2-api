<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GroupRequest extends FormRequest
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
                    'name' => [
                        'required',
                        'string',
                        'min:1',
                        'max:191',
                        Rule::unique('groups')->where('category_id', $this->category_id)->where('name', $this->name)
                    ],
                    'category_id' => 'required|exists:categories,id'
                ];
                break;
            case 'PUT':
                return [
                    'name' => [
                        'required',
                        'string',
                        'min:1',
                        'max:191',
                        Rule::unique('groups')->where('category_id', $this->category_id)->where('name', $this->name)->ignore($this->group->id),
                    ],
                    'category_id' => 'required|exists:categories,id'
                ];
                break;
        }


    }
}