<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class OptionRule implements Rule
{
    /**
     * @var string
     */
    private $value = '';

    /**
     * @var string
     */
    private $attribute;

    public function __construct($title) { $this->title = $title; }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!isset($value['id'])) {
            $this->attribute = 'id';
            return false;
        }
        if (!isset($value['content'])) {
            $this->attribute = 'content';
            return false;
        }
        if (!isset($value['type'])) {
            $this->attribute = 'type';
            return false;
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
        $this->message = __(':title :attribute is required.', [
            'value'     => $this->value,
            'attribute' => __('messages.' . $this->attribute),
        ]);
    }
}
