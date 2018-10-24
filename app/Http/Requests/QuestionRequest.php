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
                    'type' => 'required|in:single,multiple',
                    'chapter' => 'nullable|integer',
                    'section' => 'nullable|integer',
                    'options' => 'required|array|distinct',
                    'answer' => 'required|array|distinct',
                    'explain' => 'nullable|max:191',
                    'course_id' => 'required|exists:courses,id'
                ];
                break;
            case 'PUT':
                return [
                    'title' => 'required|string|min:1|max:191|unique:questions,title,' . $this->question->id,
                    'type' => 'required|in:single,multiple',
                    'options' => 'required|array|distinct',
                    'chapter' => 'nullable|integer',
                    'section' => 'nullable|integer',
                    'answer' => 'required|array|distinct',
                    'explain' => 'nullable|max:191',
                    'course_id' => 'required|exists:courses,id'
                ];
                break;
        }
    }
}
