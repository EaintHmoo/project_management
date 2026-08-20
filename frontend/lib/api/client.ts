import { ApiError } from "@/lib/api/errors";
import type { ApiResponse } from "@/lib/api/types";
import { getToken } from "@/lib/auth/token";
import { getCurrentOrganizationId } from "@/lib/organization/context";

const API_URL = process.env.NEXT_PUBLIC_API_URL ?? "http://localhost:8000/api";

interface RequestOptions extends Omit<RequestInit, "body"> {
  body?: unknown;
  /** Skip attaching the X-Organization-Id header for org-agnostic endpoints. */
  skipOrganizationHeader?: boolean;
}

async function request<T>(path: string, options: RequestOptions = {}): Promise<T> {
  const { body, skipOrganizationHeader, headers, ...rest } = options;

  const token = getToken();
  const organizationId = skipOrganizationHeader ? null : getCurrentOrganizationId();

  const response = await fetch(`${API_URL}${path}`, {
    ...rest,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
      ...(organizationId ? { "X-Organization-Id": organizationId } : {}),
      ...headers,
    },
    body: body !== undefined ? JSON.stringify(body) : undefined,
  });

  const payload = (await response.json().catch(() => null)) as ApiResponse<T> | null;

  if (!response.ok || !payload || payload.success === false) {
    const message = payload?.message ?? `Request failed with status ${response.status}`;
    const errors = payload && payload.success === false ? payload.errors : undefined;

    throw new ApiError(message, response.status, errors);
  }

  return payload.data;
}

export const apiClient = {
  get: <T>(path: string, options?: RequestOptions) => request<T>(path, { ...options, method: "GET" }),
  post: <T>(path: string, body?: unknown, options?: RequestOptions) =>
    request<T>(path, { ...options, method: "POST", body }),
  patch: <T>(path: string, body?: unknown, options?: RequestOptions) =>
    request<T>(path, { ...options, method: "PATCH", body }),
  delete: <T>(path: string, options?: RequestOptions) => request<T>(path, { ...options, method: "DELETE" }),
};
