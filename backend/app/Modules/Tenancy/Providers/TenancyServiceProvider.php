<?php

namespace App\Modules\Tenancy\Providers;

use App\Modules\Tenancy\Domain\Contracts\OrganizationRepositoryInterface;
use App\Modules\Tenancy\Domain\Contracts\TenantContext;
use App\Modules\Tenancy\Domain\Models\Organization;
use App\Modules\Tenancy\Domain\Models\OrganizationMember;
use App\Modules\Tenancy\Infrastructure\Repositories\EloquentOrganizationRepository;
use App\Modules\Tenancy\Infrastructure\RequestTenantContext;
use App\Modules\Tenancy\Presentation\Policies\OrganizationMemberPolicy;
use App\Modules\Tenancy\Presentation\Policies\OrganizationPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class TenancyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(OrganizationRepositoryInterface::class, EloquentOrganizationRepository::class);

        $this->app->scoped(RequestTenantContext::class);
        $this->app->bind(TenantContext::class, RequestTenantContext::class);
    }

    public function boot(): void
    {
        Gate::policy(Organization::class, OrganizationPolicy::class);
        Gate::policy(OrganizationMember::class, OrganizationMemberPolicy::class);
    }
}
