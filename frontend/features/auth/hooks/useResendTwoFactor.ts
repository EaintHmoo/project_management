import { useMutation } from "@tanstack/react-query";
import { resendTwoFactor } from "@/features/auth/api/resendTwoFactor";

export function useResendTwoFactor() {
  return useMutation({ mutationFn: resendTwoFactor });
}
