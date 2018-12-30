<?php

namespace Qirolab\Laravel\Bannable\Events;

use Illuminate\Database\Eloquent\Model;
use Qirolab\Laravel\Bannable\Models\Ban;
use Illuminate\Contracts\Queue\ShouldQueue;

class ModelWasBanned implements ShouldQueue
{
    /**
     * @var Model
     */
    public $model;

    /**
     * @var Ban
     */
    public $ban;

    /**
     * @param Model $bannable
     * @param Ban   $ban
     */
    public function __construct(Model $bannable, Ban $ban)
    {
        $this->model = $bannable;
        $this->ban = $ban;
    }
}
