<?php

namespace App\Modules\Tenancy\Domain\Contracts;

use App\Modules\Tenancy\Domain\Enums\OrganizationRole;
use App\Modules\Tenancy\Domain\Models\Organization;

interface TenantContext
{
    public function id(): int;

    public function organization(): Organization;

    public function role(): OrganizationRole;
}
