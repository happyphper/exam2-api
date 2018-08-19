<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
                    'name' => 'required|string|min:1|max:191|unique:categories',
                    'parent_id' => 'sometimes|exists:categories,id'
                ];
                break;
            case 'PUT':
                return [
                    'name' => 'required|string|min:1|max:191|unique:categories,name,' . $this->category,
                    'parent_id' => 'sometimes|exists:categories,id'
                ];
                break;
        }
    }
}
