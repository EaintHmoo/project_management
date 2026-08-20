<?php

namespace App\Modules\Tenancy\Domain\Events;

use App\Modules\Tenancy\Domain\Models\OrganizationMember;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class MemberInvited
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly OrganizationMember $membership,
    ) {}
}
