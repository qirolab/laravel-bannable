<?php

namespace Hkp22\Laravel\Bannable\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\Model;

class BannableTraitNotUsed extends Exception
{
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        $class = '';

        if ($message && $message instanceof Model) {
            $class = "'".get_class($message)."'";
        }

        $message = "'Hkp22\Laravel\Bannable\Traits\Bannable' trait is not used in {$class} model.";

        parent::__construct($message, $code, $previous);
    }
}
