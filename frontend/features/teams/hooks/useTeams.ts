import { useQuery } from "@tanstack/react-query";
import { getTeam, listTeams } from "@/features/teams/api/teams";

export function useTeams(organizationId: string | null) {
  return useQuery({
    queryKey: ["teams", organizationId],
    queryFn: () => listTeams(organizationId as string),
    enabled: Boolean(organizationId),
  });
}

export function useTeam(organizationId: string | null, teamId: number | null) {
  return useQuery({
    queryKey: ["teams", organizationId, teamId],
    queryFn: () => getTeam(organizationId as string, teamId as number),
    enabled: Boolean(organizationId) && Boolean(teamId),
  });
}
