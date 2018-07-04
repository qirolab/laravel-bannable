<?php

namespace Hkp22\Laravel\Bannable\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Queue\ShouldQueue;

class ModelWasUnbanned implements ShouldQueue
{
    /**
     * @var Model
     */
    public $model;

    /**
     * @param Model $bannable
     */
    public function __construct(Model $bannable)
    {
        $this->model = $bannable;
    }
}
