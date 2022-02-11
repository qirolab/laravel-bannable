<?php

namespace Qirolab\Laravel\Bannable\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BannedModelScope implements Scope
{
    /**
     * All of the extensions to be added to the builder.
     *
     * @var array
     */
    protected $extensions = [
        'WithBanned',
        'WithoutBanned',
        'OnlyBanned',
    ];

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  Builder  $builder
     * @param  Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (method_exists($model, 'disableBannedAtScope') && $model->disableBannedAtScope()) {
            return;
        }

        $builder->whereNull('banned_at');
    }

    /**
     * Extend the query builder with the needed functions.
     *
     * @param  Builder  $builder
     * @return void
     */
    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            if (method_exists($this, "scope{$extension}")) {
                $this->{"scope{$extension}"}($builder);
            }
        }
    }

    /**
     * Add the `withBanned` extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function scopeWithBanned(Builder $builder)
    {
        $builder->macro('withBanned', function (Builder $builder) {
            return $builder->withoutGlobalScope($this);
        });
    }

    /**
     * Add the `withoutBanned` extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function scopeWithoutBanned(Builder $builder)
    {
        $builder->macro('withoutBanned', function (Builder $builder) {
            return $builder->withoutGlobalScope($this)->whereNull('banned_at');
        });
    }

    /**
     * Add the `onlyBanned` extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function scopeOnlyBanned(Builder $builder)
    {
        $builder->macro('onlyBanned', function (Builder $builder) {
            return $builder->withoutGlobalScope($this)->whereNotNull('banned_at');
        });
    }
}
