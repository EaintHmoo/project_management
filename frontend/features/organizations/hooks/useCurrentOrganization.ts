"use client";

import { useEffect } from "react";
import { useOrganizations } from "@/features/organizations/hooks/useOrganizations";
import { useOrganizationStore } from "@/stores/organizationStore";

/**
 * Resolves the active organization for org-scoped features (Projects, Tasks, Meetings, Teams).
 * Falls back to the first organization the user belongs to until a real "last active org"
 * is chosen via the switcher; the choice is persisted (see stores/organizationStore.ts) so it
 * survives reloads and feeds the API client's X-Organization-Id header.
 */
export function useCurrentOrganization() {
  const { data: organizations, isLoading, isError } = useOrganizations();
  const storedId = useOrganizationStore((state) => state.organizationId);
  const isHydrated = useOrganizationStore((state) => state.isHydrated);
  const setOrganizationId = useOrganizationStore((state) => state.setOrganizationId);

  const organization =
    (storedId && organizations?.find((org) => String(org.id) === storedId)) || organizations?.[0] || null;

  useEffect(() => {
    if (isHydrated && organization && String(organization.id) !== storedId) {
      setOrganizationId(String(organization.id));
    }
  }, [isHydrated, organization, storedId, setOrganizationId]);

  return {
    organization,
    organizations: organizations ?? [],
    organizationId: organization ? String(organization.id) : null,
    isLoading: isLoading || !isHydrated,
    isError,
  };
}
