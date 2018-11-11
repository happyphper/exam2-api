<?php

namespace App\Http\Requests;

use App\Models\Question;
use App\Rules\ArrayQuestionUniqueRule;
use App\Rules\ArrayUniqueInDatabaseRule;
use App\Rules\OptionRule;
use Illuminate\Foundation\Http\FormRequest;

class BulkQuestionRequest extends FormRequest
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
            'course_id'           => ['required', 'exists:courses,id'],
            'questions'           => ['required', 'array'],
            'questions.*.title'   => ['required', 'string', 'min:1', 'max:191'],
            'questions.*.type'    => ['required', 'in:single,multiple',],
            'questions.*.option1' => ['required'],
            'questions.*.option2' => ['required'],
            'questions.*.option3' => ['sometimes'],
            'questions.*.option4' => ['sometimes'],
            'questions.*.answer'  => ['required'],
            'questions.*.explain' => ['nullable', 'regex:/.+/'],
        ];
    }
}
