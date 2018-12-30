<?php

namespace Qirolab\Tests\Laravel\Bannable\Stubs\Models;

class UserModelWithDisabledBannedScope extends User
{
    /**
     * Determine which BannedAtScope should be applied by default.
     *
     * @return bool
     */
    public function disableBannedAtScope()
    {
        return true;
    }
}
