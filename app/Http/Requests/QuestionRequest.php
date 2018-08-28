<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
                    'title' => 'required|string|min:1|max:191|unique:questions',
                    'options' => 'required|array|distinct',
                    'answers' => 'required|integer',
                    'category_id' => 'required|exists:categories,id',
                    'explain' => 'nullable|max:191',
                ];
                break;
            case 'PUT':
                return [
                    'title' => 'required|string|min:1|max:191|unique:questions,title,' . $this->question->id,
                    'options' => 'required|array|distinct',
                    'answers' => 'required|integer',
                    'category_id' => 'required|exists:categories,id',
                    'explain' => 'nullable|max:191',
                ];
                break;
        }
    }
}
