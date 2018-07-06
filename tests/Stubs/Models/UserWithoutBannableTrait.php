<?php

namespace Hkp22\Tests\Laravel\Bannable\Stubs\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserWithoutBannableTrait extends Authenticatable
{
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
