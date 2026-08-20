"use client";

import { Alert } from "@/components/ui/Alert";
import { Card } from "@/components/ui/Card";
import { InviteMemberForm } from "@/features/organizations/components/InviteMemberForm";
import { MembersList } from "@/features/organizations/components/MembersList";
import { OrganizationSettingsForm } from "@/features/organizations/components/OrganizationSettingsForm";
import { useCurrentOrganization } from "@/features/organizations/hooks/useCurrentOrganization";

export function OrganizationSettingsView() {
  const { organization, organizationId, isLoading } = useCurrentOrganization();

  if (isLoading) {
    return <p className="text-sm text-[#66746e]">Loading organization…</p>;
  }

  if (!organization || !organizationId) {
    return (
      <Card>
        <h3 className="text-lg font-bold">No organization yet</h3>
        <p className="mt-2 text-sm text-[#66746e]">Create an organization from the switcher in the top bar.</p>
      </Card>
    );
  }

  const canManage = organization.my_role === "owner" || organization.my_role === "admin";

  return (
    <div className="grid gap-6">
      <div>
        <h2 className="text-xl font-bold">Organization settings</h2>
        <p className="mt-1 text-sm text-[#66746e]">Manage {organization.name}&apos;s profile and members.</p>
      </div>

      <Card>
        <h3 className="mb-4 text-lg font-bold">Profile</h3>
        <OrganizationSettingsForm organizationId={organizationId} organization={organization} />
      </Card>

      <div>
        <h3 className="mb-3 text-lg font-bold">Members</h3>
        {canManage ? (
          <Card className="mb-4">
            <InviteMemberForm organizationId={organizationId} />
          </Card>
        ) : (
          <Alert variant="success" className="mb-4">
            Only owners and admins can invite new members.
          </Alert>
        )}
        <MembersList organizationId={organizationId} organization={organization} />
      </div>
    </div>
  );
}
