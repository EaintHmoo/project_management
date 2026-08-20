export type OrganizationRole = "owner" | "admin" | "manager" | "member" | "guest";

export interface Organization {
  id: number;
  name: string;
  slug: string;
  timezone: string;
  owner_id: number;
  my_role: OrganizationRole | null;
  created_at: string;
  updated_at: string;
}

export interface CreateOrganizationInput {
  name: string;
  slug?: string;
  timezone?: string;
}

export interface UpdateOrganizationInput {
  name?: string;
  timezone?: string;
}

export const ORGANIZATION_ROLES: OrganizationRole[] = ["owner", "admin", "manager", "member", "guest"];
