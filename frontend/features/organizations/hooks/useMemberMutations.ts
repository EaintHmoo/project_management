import { useMutation, useQueryClient } from "@tanstack/react-query";
import { removeMember, updateMemberRole } from "@/features/organizations/api/members";
import type { OrganizationRole } from "@/features/organizations/types/organization";

export function useUpdateMemberRole(organizationId: string | null) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: ({ membershipId, role }: { membershipId: number; role: OrganizationRole }) =>
      updateMemberRole(organizationId as string, membershipId, role),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["organizations", organizationId, "members"] });
    },
  });
}

export function useRemoveMember(organizationId: string | null) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (membershipId: number) => removeMember(organizationId as string, membershipId),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["organizations", organizationId, "members"] });
    },
  });
}
