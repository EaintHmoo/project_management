import { create } from "zustand";
import { clearCurrentOrganizationId, getCurrentOrganizationId, setCurrentOrganizationId } from "@/lib/organization/context";

interface OrganizationState {
  organizationId: string | null;
  isHydrated: boolean;
  hydrate: () => void;
  setOrganizationId: (organizationId: string) => void;
  clear: () => void;
}

export const useOrganizationStore = create<OrganizationState>((set) => ({
  organizationId: null,
  isHydrated: false,
  hydrate: () => set({ organizationId: getCurrentOrganizationId(), isHydrated: true }),
  setOrganizationId: (organizationId) => {
    setCurrentOrganizationId(organizationId);
    set({ organizationId });
  },
  clear: () => {
    clearCurrentOrganizationId();
    set({ organizationId: null });
  },
}));
