import { useMutation, useQueryClient } from "@tanstack/react-query";
import { createTeam, deleteTeam, updateTeam } from "@/features/teams/api/teams";
import type { CreateTeamInput, UpdateTeamInput } from "@/features/teams/types/team";

export function useCreateTeam(organizationId: string | null) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (data: CreateTeamInput) => createTeam(organizationId as string, data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["teams", organizationId] });
    },
  });
}

export function useUpdateTeam(organizationId: string | null, teamId: number) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (data: UpdateTeamInput) => updateTeam(organizationId as string, teamId, data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["teams", organizationId] });
    },
  });
}

export function useDeleteTeam(organizationId: string | null) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (teamId: number) => deleteTeam(organizationId as string, teamId),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["teams", organizationId] });
    },
  });
}
