import { useMutation } from "@tanstack/react-query";
import { verifyTwoFactor } from "@/features/auth/api/verifyTwoFactor";
import { useAuthStore } from "@/stores/authStore";

export function useVerifyTwoFactor() {
  const setSession = useAuthStore((state) => state.setSession);

  return useMutation({
    mutationFn: verifyTwoFactor,
    onSuccess: (result) => {
      setSession(result.token, result.user);
    },
  });
}
