<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestRequest extends FormRequest
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
                    'title' => 'required|string|min:1|max:191',
                    'type' => 'required|in:daily,random',
                    'started_at' => 'nullable|date',
                    'ended_at' => 'nullable|date:after:started_at'
                ];
                break;
            case 'PUT':
                return [
                    'title' => 'required|string|min:1|max:191',
                    'type' => 'required|in:daily,random',
                    'started_at' => 'nullable|date',
                    'ended_at' => 'nullable|date:after:started_at'
                ];
                break;
        }
    }
}
