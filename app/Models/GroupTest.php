<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class GroupTest extends Pivot
{
    protected $table = 'group_tests';

    public $incrementing = false;

    public $timestamps = false;
}
