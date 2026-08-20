<?php

namespace App\Modules\Tasks\Domain\Exceptions;

use RuntimeException;

class TaskNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Task not found.');
    }
}
