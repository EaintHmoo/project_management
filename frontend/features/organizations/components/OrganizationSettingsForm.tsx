"use client";

import { Alert } from "@/components/ui/Alert";
import { Input } from "@/components/ui/Input";
import { Label } from "@/components/ui/Label";
import { useUpdateOrganization } from "@/features/organizations/hooks/useOrganizationMutations";
import type { Organization } from "@/features/organizations/types/organization";
import { ApiError } from "@/lib/api/errors";

export function OrganizationSettingsForm({
  organizationId,
  organization,
}: {
  organizationId: string;
  organization: Organization;
}) {
  const updateOrganizationMutation = useUpdateOrganization(organizationId);
  const canManage = organization.my_role === "owner" || organization.my_role === "admin";

  return (
    <div className="grid gap-4">
      {updateOrganizationMutation.isError && (
        <Alert variant="error">
          {updateOrganizationMutation.error instanceof ApiError
            ? updateOrganizationMutation.error.message
            : "Could not update the organization."}
        </Alert>
      )}

      <div className="grid gap-4 sm:grid-cols-2">
        <div className="grid gap-1.5">
          <Label htmlFor="settings-name">Name</Label>
          <Input
            id="settings-name"
            defaultValue={organization.name}
            disabled={!canManage}
            onBlur={(e) =>
              canManage && e.target.value !== organization.name && updateOrganizationMutation.mutate({ name: e.target.value })
            }
          />
        </div>

        <div className="grid gap-1.5">
          <Label htmlFor="settings-timezone">Timezone</Label>
          <Input
            id="settings-timezone"
            defaultValue={organization.timezone}
            disabled={!canManage}
            onBlur={(e) =>
              canManage &&
              e.target.value !== organization.timezone &&
              updateOrganizationMutation.mutate({ timezone: e.target.value })
            }
          />
        </div>
      </div>

      <div className="grid gap-1.5">
        <Label>Slug</Label>
        <p className="text-sm text-[#66746e]">{organization.slug}</p>
      </div>

      {!canManage && <p className="text-xs text-[#9aa39c]">Only owners and admins can edit organization settings.</p>}
    </div>
  );
}
