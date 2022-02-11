<?php

namespace Qirolab\Laravel\Bannable\Traits;

use Qirolab\Laravel\Bannable\Scopes\BannedModelScope;

trait HasBannedModelScope
{
    /**
     * Boot the HasBannedAtScope trait for a model.
     *
     * @return void
     */
    public static function bootHasBannedModelScope()
    {
        static::addGlobalScope(new BannedModelScope());
    }
}
