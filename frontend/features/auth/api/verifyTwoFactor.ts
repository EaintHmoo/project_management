import { apiClient } from "@/lib/api/client";
import type { TwoFactorVerifyResult } from "@/features/auth/types/auth";

export function verifyTwoFactor(data: { challenge_id: number; code: string }): Promise<TwoFactorVerifyResult> {
  return apiClient.post<TwoFactorVerifyResult>("/auth/two-factor/verify", data, {
    skipOrganizationHeader: true,
  });
}
