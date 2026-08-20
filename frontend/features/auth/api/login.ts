import { apiClient } from "@/lib/api/client";
import type { LoginFormValues } from "@/features/auth/schemas/loginSchema";
import type { LoginChallenge } from "@/features/auth/types/auth";

export function login(data: LoginFormValues): Promise<LoginChallenge> {
  return apiClient.post<LoginChallenge>("/auth/login", data, { skipOrganizationHeader: true });
}
