import { apiClient } from "@/lib/api/client";
import type { RegisterFormValues } from "@/features/auth/schemas/registerSchema";
import type { RegisteredUser } from "@/features/auth/types/auth";

export function register(data: RegisterFormValues): Promise<RegisteredUser> {
  return apiClient.post<RegisteredUser>("/auth/register", data, { skipOrganizationHeader: true });
}
