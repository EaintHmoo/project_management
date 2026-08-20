import { useMutation, useQueryClient } from "@tanstack/react-query";
import { logout } from "@/features/auth/api/logout";
import { useAuthStore } from "@/stores/authStore";
import { useOrganizationStore } from "@/stores/organizationStore";

export function useLogout() {
  const clearSession = useAuthStore((state) => state.clearSession);
  const clearOrganization = useOrganizationStore((state) => state.clear);
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: logout,
    onSettled: () => {
      clearSession();
      clearOrganization();
      queryClient.clear();
    },
  });
}
