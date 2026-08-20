<?php

namespace App\Modules\Tenancy\Domain\Enums;

enum MembershipStatus: string
{
    case Invited = 'invited';
    case Active = 'active';
    case Declined = 'declined';
}
