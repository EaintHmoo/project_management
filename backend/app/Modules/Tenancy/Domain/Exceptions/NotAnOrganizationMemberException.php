<?php

namespace App\Modules\Tenancy\Domain\Exceptions;

use RuntimeException;

class NotAnOrganizationMemberException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('You are not an active member of this organization.');
    }
}
