<?php

namespace App\Modules\Projects\Domain\Exceptions;

use RuntimeException;

class ProjectNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Project not found.');
    }
}
