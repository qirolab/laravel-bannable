<?php

namespace Qirolab\Laravel\Bannable\Events;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;

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
