<?php

namespace Qirolab\Laravel\Bannable\Traits;

use Carbon\Carbon;
use Qirolab\Laravel\Bannable\Models\Ban;

trait Bannable
{
    use HasBannedModelScope;

    /**
     * Entity Bans.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bans()
    {
        return $this->morphMany(Ban::class, 'bannable');
    }

    /**
     * Ban model.
     *
     * @param  null|array $attributes
     * @return Ban
     */
    public function ban(array $attributes = [])
    {
        return $this->bans()->create($attributes);
    }

    /**
     * Remove ban from model.
     *
     * @return void
     */
    public function unban()
    {
        $this->bans->each(function ($ban) {
            $ban->delete();
        });
    }

    /**
     * Set banned flag.
     *
     * @return $this
     */
    public function setBannedFlag()
    {
        $this->banned_at = Carbon::now();

        return $this;
    }

    /**
     * Unset banned flag.
     *
     * @return $this
     */
    public function unsetBannedFlag()
    {
        $this->banned_at = null;

        return $this;
    }

    /**
     * If model is banned.
     *
     * @return bool
     */
    public function isBanned()
    {
        return $this->banned_at !== null;
    }

    /**
     * If model is not banned.
     *
     * @return bool
     */
    public function isNotBanned()
    {
        return ! $this->isBanned();
    }
}
