import { apiClient } from "@/lib/api/client";
import type { Label } from "@/features/tasks/types/task";

export function listLabels(organizationId: string): Promise<Label[]> {
  return apiClient.get<Label[]>(`/organizations/${organizationId}/labels`);
}

export function createLabel(organizationId: string, name: string, color: string): Promise<Label> {
  return apiClient.post<Label>(`/organizations/${organizationId}/labels`, { name, color });
}

export function deleteLabel(organizationId: string, labelId: number): Promise<null> {
  return apiClient.delete<null>(`/organizations/${organizationId}/labels/${labelId}`);
}
