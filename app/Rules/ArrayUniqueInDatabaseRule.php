<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ArrayUniqueInDatabaseRule implements Rule
{
    /**
     * @var string
     */
    private $value;
    /**
     * @var string|array
     */
    private $attribute;
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    private $model;

    public function __construct($attribute, \Illuminate\Database\Eloquent\Model $model) {
        $this->attribute = $attribute;
        $this->model = $model;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  array  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (is_array($value)) {
            foreach ($value as $field) {
                if ($this->model->where($this->attribute, $field)->exists()) {
                    $this->value = $field;
                    return false;
                }
            }
        }
        $this->value = $value;
        return !$this->model->where($this->attribute, $value)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __(':value :attribute has already been used.', [
            'value'     => $this->value,
            'attribute' => __('messages.' . $this->attribute),
        ]);
    }
}
