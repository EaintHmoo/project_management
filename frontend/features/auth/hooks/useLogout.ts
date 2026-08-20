import { useMutation, useQueryClient } from "@tanstack/react-query";
import { logout } from "@/features/auth/api/logout";
import { useAuthStore } from "@/stores/authStore";

export function useLogout() {
  const clearSession = useAuthStore((state) => state.clearSession);
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: logout,
    onSettled: () => {
      clearSession();
      queryClient.clear();
    },
  });
}
