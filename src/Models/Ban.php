<?php

namespace Qirolab\Laravel\Bannable\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Qirolab\Laravel\Bannable\Scopes\BannedModelScope;

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
     * @param  \Carbon\Carbon|string  $value
     * @return void
     */
    public function setExpiredAtAttribute($value)
    {
        if (! $value instanceof Carbon) {
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
        return $this->morphTo('bannable')->withoutGlobalScope(BannedModelScope::class);
    }

    /**
     * Delete all expired Ban models.
     *
     * @return void
     */
    public static function deleteExpired()
    {
        $bans = self::where('expired_at', '<=', Carbon::now()->format('Y-m-d H:i:s'))->get();

        $bans->each(function (Ban $ban) {
            $ban->delete();
        });
    }

    /**
     * Scope a query to only include models by owner.
     *
     * @param  Builder  $query
     * @param  Model  $bannable
     * @return Builder
     */
    public function scopeWhereBannable(Builder $query, Model $bannable)
    {
        return $query->where([
            'bannable_id' => $bannable->getKey(),
            'bannable_type' => $bannable->getMorphClass(),
        ]);
    }
}
