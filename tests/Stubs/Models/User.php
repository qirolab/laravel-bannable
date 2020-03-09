<?php

namespace Qirolab\Tests\Laravel\Bannable\Stubs\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Qirolab\Laravel\Bannable\Traits\Bannable;

class User extends Authenticatable
{
    use Bannable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
