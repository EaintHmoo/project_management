import { useQuery } from "@tanstack/react-query";
import { getOrganizationMembers } from "@/features/organizations/api/organizations";

export function useOrganizationMembers(organizationId: string | null) {
  return useQuery({
    queryKey: ["organizations", organizationId, "members"],
    queryFn: () => getOrganizationMembers(organizationId as string),
    enabled: Boolean(organizationId),
  });
}
