<?php

namespace Hkp22\Laravel\Bannable\Traits;

use Hkp22\Laravel\Bannable\Scopes\BannedModelScope;

trait HasBannedModelScope
{
    /**
     * Boot the HasBannedAtScope trait for a model.
     *
     * @return void
     */
    public static function bootHasBannedModelScope()
    {
        static::addGlobalScope(new BannedModelScope);
    }
}
