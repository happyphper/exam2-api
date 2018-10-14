<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ArrayUserUniqueRule implements Rule
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
        foreach ($value as $item) {
            if (isset($item['phone']) && collect($value)->where('phone', $item['phone'])->count() > 1) {
                $this->value = $item['phone'];
                $this->attribute = 'phone';
                return false;
            }
            if (isset($item['email']) && collect($value)->where('email', $item['email'])->count() > 1) {
                $this->value = $item['email'];
                $this->attribute = 'email';
                return false;
            }
            if (isset($item['student_id']) && collect($value)->where('student_id', $item['student_id'])->count() > 1) {
                $this->value = $item['student_id'];
                $this->attribute = 'student_id';
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
        return __(':title :attribute repeating in form data.', [
            'value'     => $this->value,
            'attribute' => __('messages.' . $this->attribute),
        ]);
    }
}
