<?php

namespace App\Modules\Tenancy\Presentation\Http\Middleware;

use App\Modules\Tenancy\Domain\Enums\MembershipStatus;
use App\Modules\Tenancy\Domain\Models\Organization;
use App\Modules\Tenancy\Infrastructure\RequestTenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenantContext
{
    public function __construct(
        private readonly RequestTenantContext $tenantContext,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $organizationId = $request->route('organization') instanceof Organization
            ? $request->route('organization')->id
            : $request->header('X-Organization-Id');

        if (! $organizationId) {
            return response()->json([
                'success' => false,
                'message' => 'An organization context is required for this request.',
            ], 400);
        }

        $organization = Organization::find($organizationId);

        $membership = $organization?->memberships()
            ->where('user_id', $request->user()->id)
            ->where('status', MembershipStatus::Active->value)
            ->first();

        if (! $organization || ! $membership) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this organization.',
            ], 403);
        }

        $this->tenantContext->resolve($organization, $membership);

        return $next($request);
    }
}
