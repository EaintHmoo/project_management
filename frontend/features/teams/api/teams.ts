import { apiClient } from "@/lib/api/client";
import type { CreateTeamInput, Team, UpdateTeamInput } from "@/features/teams/types/team";

export function listTeams(organizationId: string): Promise<Team[]> {
  return apiClient.get<Team[]>(`/organizations/${organizationId}/teams`);
}

export function getTeam(organizationId: string, teamId: number): Promise<Team> {
  return apiClient.get<Team>(`/organizations/${organizationId}/teams/${teamId}`);
}

export function createTeam(organizationId: string, data: CreateTeamInput): Promise<Team> {
  return apiClient.post<Team>(`/organizations/${organizationId}/teams`, data);
}

export function updateTeam(organizationId: string, teamId: number, data: UpdateTeamInput): Promise<Team> {
  return apiClient.patch<Team>(`/organizations/${organizationId}/teams/${teamId}`, data);
}

export function deleteTeam(organizationId: string, teamId: number): Promise<null> {
  return apiClient.delete<null>(`/organizations/${organizationId}/teams/${teamId}`);
}
