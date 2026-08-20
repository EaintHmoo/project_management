"use client";

import Link from "next/link";
import { Button } from "@/components/ui/Button";
import { Card } from "@/components/ui/Card";
import {
  useDeleteNotification,
  useMarkAllNotificationsAsRead,
  useMarkNotificationAsRead,
} from "@/features/notifications/hooks/useNotificationMutations";
import { useNotifications } from "@/features/notifications/hooks/useNotifications";
import type { AppNotification } from "@/features/notifications/types/notification";

export function NotificationsView() {
  const { data: notifications, isLoading } = useNotifications();
  const markAsReadMutation = useMarkNotificationAsRead();
  const markAllAsReadMutation = useMarkAllNotificationsAsRead();
  const deleteMutation = useDeleteNotification();

  const hasUnread = (notifications ?? []).some((n) => !n.read_at);

  if (isLoading) {
    return <p className="text-sm text-[#66746e]">Loading notifications…</p>;
  }

  const onOpen = (notification: AppNotification) => {
    if (!notification.read_at) {
      markAsReadMutation.mutate(notification.id);
    }
  };

  return (
    <div className="grid gap-6">
      <div className="flex items-center justify-between">
        <h2 className="text-xl font-bold">Notifications</h2>
        {hasUnread && (
          <Button
            variant="secondary"
            size="sm"
            isLoading={markAllAsReadMutation.isPending}
            onClick={() => markAllAsReadMutation.mutate()}
          >
            Mark all as read
          </Button>
        )}
      </div>

      {notifications && notifications.length > 0 ? (
        <div className="grid gap-3">
          {notifications.map((notification) => (
            <Card
              key={notification.id}
              className={`flex items-start justify-between gap-4 ${notification.read_at ? "" : "border-[#12312b]"}`}
            >
              <div className="min-w-0">
                {notification.data.action_url ? (
                  <Link
                    href={notification.data.action_url}
                    onClick={() => onOpen(notification)}
                    className="font-semibold text-[#18201f] hover:underline"
                  >
                    {notification.data.title}
                  </Link>
                ) : (
                  <p className="font-semibold text-[#18201f]">{notification.data.title}</p>
                )}
                <p className="mt-1 text-sm text-[#66746e]">{notification.data.body}</p>
                <p className="mt-2 text-xs text-[#9aa39c]">{new Date(notification.created_at).toLocaleString()}</p>
              </div>

              <div className="flex shrink-0 items-center gap-3">
                {!notification.read_at && (
                  <button
                    type="button"
                    className="text-xs font-semibold text-[#12312b] hover:underline"
                    onClick={() => markAsReadMutation.mutate(notification.id)}
                  >
                    Mark read
                  </button>
                )}
                <button
                  type="button"
                  className="text-xs font-semibold text-[#c94f38] hover:underline"
                  onClick={() => deleteMutation.mutate(notification.id)}
                >
                  Delete
                </button>
              </div>
            </Card>
          ))}
        </div>
      ) : (
        <Card>
          <p className="text-sm text-[#66746e]">You&apos;re all caught up.</p>
        </Card>
      )}
    </div>
  );
}
