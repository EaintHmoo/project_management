import { apiClient } from "@/lib/api/client";
import type { CreateProjectInput, Project, UpdateProjectInput } from "@/features/projects/types/project";

export function listProjects(organizationId: string): Promise<Project[]> {
  return apiClient.get<Project[]>(`/organizations/${organizationId}/projects`);
}

export function getProject(organizationId: string, projectId: number): Promise<Project> {
  return apiClient.get<Project>(`/organizations/${organizationId}/projects/${projectId}`);
}

export function createProject(organizationId: string, data: CreateProjectInput): Promise<Project> {
  return apiClient.post<Project>(`/organizations/${organizationId}/projects`, data);
}

export function updateProject(
  organizationId: string,
  projectId: number,
  data: UpdateProjectInput,
): Promise<Project> {
  return apiClient.patch<Project>(`/organizations/${organizationId}/projects/${projectId}`, data);
}

export function archiveProject(organizationId: string, projectId: number): Promise<Project> {
  return apiClient.post<Project>(`/organizations/${organizationId}/projects/${projectId}/archive`);
}

export function deleteProject(organizationId: string, projectId: number): Promise<null> {
  return apiClient.delete<null>(`/organizations/${organizationId}/projects/${projectId}`);
}
