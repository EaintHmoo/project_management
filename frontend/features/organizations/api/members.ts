import { apiClient } from "@/lib/api/client";
import type { OrganizationMember, OrganizationRole } from "@/features/organizations/types/member";

export function updateMemberRole(
  organizationId: string,
  membershipId: number,
  role: OrganizationRole,
): Promise<OrganizationMember> {
  return apiClient.patch<OrganizationMember>(`/organizations/${organizationId}/members/${membershipId}`, { role });
}

export function removeMember(organizationId: string, membershipId: number): Promise<null> {
  return apiClient.delete<null>(`/organizations/${organizationId}/members/${membershipId}`);
}
