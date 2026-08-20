<?php

namespace App\Modules\Tenancy\Tests\Unit;

use App\Modules\Tenancy\Application\Services\CreateOrganizationService;
use App\Modules\Tenancy\Domain\Contracts\OrganizationRepositoryInterface;
use App\Modules\Tenancy\Domain\DTOs\CreateOrganizationData;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class CreateOrganizationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_organization_and_attaches_the_owner_as_active_member(): void
    {
        $data = new CreateOrganizationData(ownerId: 1, name: 'Acme Inc', slug: null, timezone: 'UTC');

        $organization = Mockery::mock(Organization::class)->makePartial();
        $members = Mockery::mock(BelongsToMany::class);
        $members->shouldReceive('attach')->once();
        $organization->shouldReceive('members')->once()->andReturn($members);

        $repository = Mockery::mock(OrganizationRepositoryInterface::class);
        $repository->shouldReceive('create')
            ->once()
            ->with(Mockery::on(fn (CreateOrganizationData $d) => $d->name === 'Acme Inc' && $d->slug === 'acme-inc'))
            ->andReturn($organization);
        $repository->shouldReceive('findBySlug')->with('acme-inc')->once()->andReturnNull();

        $service = new CreateOrganizationService($repository);

        $result = $service->execute($data);

        $this->assertSame($organization, $result);
    }
}
