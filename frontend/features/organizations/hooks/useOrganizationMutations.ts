import { useMutation, useQueryClient } from "@tanstack/react-query";
import { createOrganization, switchOrganization, updateOrganization } from "@/features/organizations/api/organizations";
import type { CreateOrganizationInput, UpdateOrganizationInput } from "@/features/organizations/types/organization";
import { useOrganizationStore } from "@/stores/organizationStore";

export function useCreateOrganization() {
  const queryClient = useQueryClient();
  const setOrganizationId = useOrganizationStore((state) => state.setOrganizationId);

  return useMutation({
    mutationFn: (data: CreateOrganizationInput) => createOrganization(data),
    onSuccess: (organization) => {
      queryClient.invalidateQueries({ queryKey: ["organizations"] });
      setOrganizationId(String(organization.id));
    },
  });
}

export function useUpdateOrganization(organizationId: string | null) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (data: UpdateOrganizationInput) => updateOrganization(organizationId as string, data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["organizations"] });
    },
  });
}

export function useSwitchOrganization() {
  const queryClient = useQueryClient();
  const setOrganizationId = useOrganizationStore((state) => state.setOrganizationId);

  return useMutation({
    mutationFn: (organizationId: string) => switchOrganization(organizationId),
    onSuccess: (organization) => {
      setOrganizationId(String(organization.id));
      queryClient.invalidateQueries();
    },
  });
}
