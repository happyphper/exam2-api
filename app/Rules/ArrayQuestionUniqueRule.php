<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ArrayQuestionUniqueRule implements Rule
{
    /**
     * @var string
     */
    private $value = '';

    /**
     * @var string
     */
    private $attribute = '';

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach ($value as $question) {
            if (collect($value)->where('title', $question['title'])->count() > 1) {
                $this->value = $question['title'];
                $this->attribute = 'title';
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __(':value :attribute repeating in form data.', [
            'value' => $this->value,
            'attribute' => __('messages.' . $this->attribute)
        ]);
    }
}
