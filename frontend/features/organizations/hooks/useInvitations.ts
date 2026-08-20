import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { acceptInvitation, declineInvitation, inviteMember, listMyInvitations } from "@/features/organizations/api/invitations";
import type { OrganizationRole } from "@/features/organizations/types/organization";

export function useMyInvitations() {
  return useQuery({
    queryKey: ["invitations", "mine"],
    queryFn: listMyInvitations,
  });
}

export function useInviteMember(organizationId: string | null) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: ({ email, role }: { email: string; role: OrganizationRole }) =>
      inviteMember(organizationId as string, email, role),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["organizations", organizationId, "members"] });
    },
  });
}

export function useAcceptInvitation() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (membershipId: number) => acceptInvitation(membershipId),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["invitations", "mine"] });
      queryClient.invalidateQueries({ queryKey: ["organizations"] });
    },
  });
}

export function useDeclineInvitation() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (membershipId: number) => declineInvitation(membershipId),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["invitations", "mine"] });
    },
  });
}
