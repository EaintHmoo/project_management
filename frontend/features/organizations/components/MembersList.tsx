"use client";

import { Card } from "@/components/ui/Card";
import { useOrganizationMembers } from "@/features/organizations/hooks/useOrganizationMembers";
import { useRemoveMember, useUpdateMemberRole } from "@/features/organizations/hooks/useMemberMutations";
import { ORGANIZATION_ROLES, type Organization } from "@/features/organizations/types/organization";
import { useAuthStore } from "@/stores/authStore";

export function MembersList({ organizationId, organization }: { organizationId: string; organization: Organization }) {
  const currentUser = useAuthStore((state) => state.user);
  const { data: members, isLoading } = useOrganizationMembers(organizationId);
  const updateRoleMutation = useUpdateMemberRole(organizationId);
  const removeMemberMutation = useRemoveMember(organizationId);
  const canManage = organization.my_role === "owner" || organization.my_role === "admin";

  if (isLoading) {
    return <p className="text-sm text-[#66746e]">Loading members…</p>;
  }

  return (
    <div className="grid gap-2">
      {(members ?? []).map((member) => {
        const isSelf = member.user.id === currentUser?.id;
        const isOwner = member.role === "owner";

        return (
          <Card key={member.id} className="flex flex-wrap items-center justify-between gap-3">
            <div>
              <p className="font-medium">{member.user.name}</p>
              <p className="text-xs text-[#66746e]">{member.user.email}</p>
            </div>

            <div className="flex items-center gap-2">
              <select
                defaultValue={member.role}
                disabled={!canManage || isOwner}
                className="h-9 rounded-md border border-[#d8cfbd] bg-white px-2 text-sm outline-none focus:border-[#12312b] disabled:opacity-50"
                onChange={(e) =>
                  updateRoleMutation.mutate({ membershipId: member.id, role: e.target.value as (typeof ORGANIZATION_ROLES)[number] })
                }
              >
                {ORGANIZATION_ROLES.map((role) => (
                  <option key={role} value={role}>
                    {role}
                  </option>
                ))}
              </select>

              {canManage && !isOwner && !isSelf && (
                <button
                  type="button"
                  className="text-xs font-semibold text-[#c94f38] hover:underline"
                  onClick={() => {
                    if (window.confirm(`Remove ${member.user.name} from this organization?`)) {
                      removeMemberMutation.mutate(member.id);
                    }
                  }}
                >
                  Remove
                </button>
              )}
            </div>
          </Card>
        );
      })}
      {members && members.length === 0 && <p className="text-sm text-[#9aa39c]">No members yet.</p>}
    </div>
  );
}
