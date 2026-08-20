import { useQuery } from "@tanstack/react-query";
import { getWorkspaceOverview } from "@/features/dashboard/api/getWorkspaceOverview";

export function useWorkspaceOverview() {
  return useQuery({
    queryKey: ["workspace", "overview"],
    queryFn: getWorkspaceOverview,
  });
}
