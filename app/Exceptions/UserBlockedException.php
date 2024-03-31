<?php

namespace App\Exceptions;

use Exception;

class UserBlockedException extends Exception
{
    protected $reason;

    public function __construct($reason = 'You have been blocked by an administrator', $code = 403, Exception $previous = null) {
        $this->reason = $reason;
        parent::__construct('User blocked', $code, $previous);
    }

    public function getReason() {
        return $this->reason;
    }
}
