const ORGANIZATION_KEY = "current_organization_id";

export function getCurrentOrganizationId(): string | null {
  if (typeof window === "undefined") {
    return null;
  }

  return window.localStorage.getItem(ORGANIZATION_KEY);
}

export function setCurrentOrganizationId(organizationId: string): void {
  window.localStorage.setItem(ORGANIZATION_KEY, organizationId);
}

export function clearCurrentOrganizationId(): void {
  window.localStorage.removeItem(ORGANIZATION_KEY);
}
