<?php

namespace Qirolab\Laravel\Bannable\Exceptions;

use Exception;

class BannableTraitNotUsed extends Exception
{
    public function __construct(string $class = null, int $code = 0, Exception $previous = null)
    {
        $message = "`Qirolab\Laravel\Bannable\Traits\Bannable` trait is not used in `{$class}` model.";

        parent::__construct($message, $code, $previous);
    }
}
