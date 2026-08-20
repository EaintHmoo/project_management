import { apiClient } from "@/lib/api/client";
import type { AppNotification } from "@/features/notifications/types/notification";

export function listNotifications(): Promise<AppNotification[]> {
  return apiClient.get<AppNotification[]>("/notifications", { skipOrganizationHeader: true });
}

export function getUnreadCount(): Promise<{ count: number }> {
  return apiClient.get<{ count: number }>("/notifications/unread-count", { skipOrganizationHeader: true });
}

export function markNotificationAsRead(id: string): Promise<AppNotification> {
  return apiClient.post<AppNotification>(`/notifications/${id}/read`, undefined, { skipOrganizationHeader: true });
}

export function markAllNotificationsAsRead(): Promise<null> {
  return apiClient.post<null>("/notifications/read-all", undefined, { skipOrganizationHeader: true });
}

export function deleteNotification(id: string): Promise<null> {
  return apiClient.delete<null>(`/notifications/${id}`, { skipOrganizationHeader: true });
}
