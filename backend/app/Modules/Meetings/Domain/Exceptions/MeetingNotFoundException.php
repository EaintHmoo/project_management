<?php

namespace App\Modules\Meetings\Domain\Exceptions;

use RuntimeException;

class MeetingNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Meeting not found.');
    }
}
