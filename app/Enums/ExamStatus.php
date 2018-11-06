<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class ExamStatus extends Enum implements LocalizedEnum
{
    /**
     * @const string 未开始
     */
    const Unstart = 'unstart';
    /**
     * @const string 进行中
     */
    const Ongoing = 'ongoing';
    /**
     * @const string 已结束
     */
    const End = 'end';
}
