<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TestQuestionRequest extends FormRequest
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
                    'question_id' => [
                        'required',
                        'exists:questions,id',
                        Rule::unique('test_questions')->where('question_id', $this->question_id)->where('test_id', $this->test->id)
                    ],
                    'score' => [
                        'required',
                        'integer',
                        'max:100'
                    ]
                ];
                break;
        }
    }
}
