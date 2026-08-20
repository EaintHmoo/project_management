import { apiClient } from "@/lib/api/client";
import type { CreateOrganizationInput, Organization, UpdateOrganizationInput } from "@/features/organizations/types/organization";
import type { OrganizationMember } from "@/features/organizations/types/member";

export function listOrganizations(): Promise<Organization[]> {
  return apiClient.get<Organization[]>("/organizations", { skipOrganizationHeader: true });
}

export function getOrganization(organizationId: string): Promise<Organization> {
  return apiClient.get<Organization>(`/organizations/${organizationId}`);
}

export function createOrganization(data: CreateOrganizationInput): Promise<Organization> {
  return apiClient.post<Organization>("/organizations", data, { skipOrganizationHeader: true });
}

export function updateOrganization(organizationId: string, data: UpdateOrganizationInput): Promise<Organization> {
  return apiClient.patch<Organization>(`/organizations/${organizationId}`, data);
}

export function switchOrganization(organizationId: string): Promise<Organization> {
  return apiClient.post<Organization>(`/organizations/${organizationId}/switch`);
}

export function getOrganizationMembers(organizationId: string): Promise<OrganizationMember[]> {
  return apiClient.get<OrganizationMember[]>(`/organizations/${organizationId}/members`);
}
