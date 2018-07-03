<?php

namespace Hkp22\Laravel\Bannable\Observers;

use Hkp22\Laravel\Bannable\Models\Ban;

class BanObserver
{
    /**
     * Handle the creating event for the Ban model.
     *
     * @param  Ban  $ban
     * @return void
     */
    public function creating(Ban $ban)
    {
        $bannedBy = auth()->user();

        if ($bannedBy) {
            $ban->forceFill([
                'created_by_id' => $bannedBy->getKey(),
                'created_by_type' => $bannedBy->getMorphClass(),
            ]);
        }
    }

    /**
     * Handle the created event for the Ban model.
     *
     * @param  Ban  $ban
     * @return void
     */
    public function created(Ban $ban)
    {
        $bannable = $ban->bannable;

        $bannable->setBannedFlag($ban->created_at)->save();
    }

    /**
     * Handle the deleted event for the Ban model.
     *
     * @param  Ban  $ban
     * @return void
     */
    public function deleted(Ban $ban)
    {
        $bannable = $ban->bannable;

        if ($bannable->bans->count() === 0) {
            $bannable->unsetBannedFlag()->save();
        }
    }
}
