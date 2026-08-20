import { apiClient } from "@/lib/api/client";
import type { LoginChallenge } from "@/features/auth/types/auth";

export function resendTwoFactor(data: { challenge_id: number }): Promise<LoginChallenge> {
  return apiClient.post<LoginChallenge>("/auth/two-factor/resend", data, { skipOrganizationHeader: true });
}
