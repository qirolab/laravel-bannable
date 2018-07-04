<?php

namespace Hkp22\Laravel\Bannable\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ban extends Model
{
    use SoftDeletes;

    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'bans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['comment', 'expired_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'expired_at' => 'datetime',
    ];

    /**
     * Expired timestamp mutator.
     *
     * @param \Carbon\Carbon|string $value
     */
    public function setExpiredAtAttribute($value)
    {
        if (!$value instanceof Carbon) {
            $value = Carbon::parse($value);
        }

        $this->attributes['expired_at'] = $value;
    }

    /**
     * Entity responsible for ban.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function createdBy()
    {
        return $this->morphTo('created_by');
    }

    /**
     * Bannable model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function bannable()
    {
        return $this->morphTo('bannable');
    }

    /**
     * Delete all expired Ban models.
     *
     * @return void
     */
    public static function deleteExpired()
    {
        $bans = Ban::where('expired_at', '<=', Carbon::now()->format('Y-m-d H:i:s'))->get();

        $bans->each(function ($ban) {
            $ban->delete();
        });
    }
}
