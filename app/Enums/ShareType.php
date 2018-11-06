<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class ShareType extends Enum implements LocalizedEnum
{
    /**
     * @const string 班级
     */
    const Classroom = 'classroom';
    /**
     * @const string 题目
     */
    const Question = 'question';
}
