import { apiClient } from "@/lib/api/client";
import type { OrganizationMember, OrganizationRole } from "@/features/organizations/types/member";

export function inviteMember(organizationId: string, email: string, role: OrganizationRole): Promise<OrganizationMember> {
  return apiClient.post<OrganizationMember>(`/organizations/${organizationId}/invitations`, { email, role });
}

export function listMyInvitations(): Promise<OrganizationMember[]> {
  return apiClient.get<OrganizationMember[]>("/invitations", { skipOrganizationHeader: true });
}

export function acceptInvitation(membershipId: number): Promise<OrganizationMember> {
  return apiClient.post<OrganizationMember>(`/invitations/${membershipId}/accept`, undefined, {
    skipOrganizationHeader: true,
  });
}

export function declineInvitation(membershipId: number): Promise<null> {
  return apiClient.post<null>(`/invitations/${membershipId}/decline`, undefined, { skipOrganizationHeader: true });
}
