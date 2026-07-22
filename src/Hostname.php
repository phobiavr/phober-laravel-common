<?php

namespace Phobiavr\PhoberLaravelCommon;

use Illuminate\Database\Eloquent\Model;

class Hostname extends Model
{
    protected $connection = 'db_shared';

    protected $fillable = ['hostname', 'container'];
}
