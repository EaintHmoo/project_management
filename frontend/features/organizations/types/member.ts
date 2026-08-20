import type { OrganizationRole } from "@/features/organizations/types/organization";

export type { OrganizationRole };

export type MembershipStatus = "invited" | "active" | "declined";

export interface OrganizationMemberUser {
  id: number;
  name: string;
  email: string;
}

export interface OrganizationMember {
  id: number;
  organization_id: number;
  user: OrganizationMemberUser;
  role: OrganizationRole;
  status: MembershipStatus;
  invited_by_id: number | null;
  invited_at: string | null;
  joined_at: string | null;
}
