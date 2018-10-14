<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ArrayOptionRule implements Rule
{
    /**
     * @var string
     */
    private $attribute = '';
    /**
     * @var string
     */
    private $value = '';

    public function __construct($title) { $this->value = $title; }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach ($value as $option) {
            if (!isset($option['id'])) {
                $this->attribute = 'id';
                return false;
            }
            if (!isset($option['content'])) {
                $this->attribute = 'content';
                return false;
            }
            if (!isset($option['type'])) {
                $this->attribute = 'type';
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
        $this->message = __(':value :attribute is required.', [
            'value'     => $this->value,
            'attribute' => __('messages.' . $this->attribute),
        ]);
    }
}
