import { apiClient } from "@/lib/api/client";
import type { WorkspaceOverview } from "@/features/dashboard/types/overview";

export function getWorkspaceOverview(): Promise<WorkspaceOverview | null> {
  return apiClient.get<WorkspaceOverview | null>("/workspace/overview");
}
