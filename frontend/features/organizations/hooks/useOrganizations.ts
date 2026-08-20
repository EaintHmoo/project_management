import { useQuery } from "@tanstack/react-query";
import { getOrganization, listOrganizations } from "@/features/organizations/api/organizations";

export function useOrganizations() {
  return useQuery({
    queryKey: ["organizations"],
    queryFn: listOrganizations,
  });
}

export function useOrganization(organizationId: string | null) {
  return useQuery({
    queryKey: ["organizations", organizationId],
    queryFn: () => getOrganization(organizationId as string),
    enabled: Boolean(organizationId),
  });
}
