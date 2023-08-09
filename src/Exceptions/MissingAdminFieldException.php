<?php

namespace Elemenx\CirFrameworkSkeleton\Exceptions;

use RuntimeException;

class MissingAdminFieldException extends RuntimeException
{
    /**
     * Create a new exception instance.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($message = 'No model admin field has been specified.')
    {
        parent::__construct($message);
    }
}
