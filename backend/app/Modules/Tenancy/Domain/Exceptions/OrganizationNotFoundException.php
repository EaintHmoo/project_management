<?php

namespace App\Modules\Tenancy\Domain\Exceptions;

use RuntimeException;

class OrganizationNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Organization not found.');
    }
}
