import { useQuery } from "@tanstack/react-query";
import { getProject, listProjects } from "@/features/projects/api/projects";

export function useProjects(organizationId: string | null) {
  return useQuery({
    queryKey: ["projects", organizationId],
    queryFn: () => listProjects(organizationId as string),
    enabled: Boolean(organizationId),
  });
}

export function useProject(organizationId: string | null, projectId: number | null) {
  return useQuery({
    queryKey: ["projects", organizationId, projectId],
    queryFn: () => getProject(organizationId as string, projectId as number),
    enabled: Boolean(organizationId) && Boolean(projectId),
  });
}
