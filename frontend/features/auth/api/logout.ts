import { apiClient } from "@/lib/api/client";

export function logout(): Promise<null> {
  return apiClient.post<null>("/auth/logout", undefined, { skipOrganizationHeader: true });
}
