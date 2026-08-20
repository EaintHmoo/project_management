import { useQuery } from "@tanstack/react-query";
import { getWorkspaceOverview } from "@/features/dashboard/api/getWorkspaceOverview";

export function useWorkspaceOverview(organizationId: string | null) {
  return useQuery({
    queryKey: ["workspace", "overview", organizationId],
    queryFn: getWorkspaceOverview,
    enabled: Boolean(organizationId),
  });
}
