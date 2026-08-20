"use client";

import { Bell } from "lucide-react";
import Link from "next/link";
import { useState } from "react";
import { useMarkNotificationAsRead } from "@/features/notifications/hooks/useNotificationMutations";
import { useNotifications, useUnreadNotificationCount } from "@/features/notifications/hooks/useNotifications";
import type { AppNotification } from "@/features/notifications/types/notification";

function timeAgo(isoDate: string): string {
  const seconds = Math.max(0, Math.floor((Date.now() - new Date(isoDate).getTime()) / 1000));
  if (seconds < 60) return "just now";
  const minutes = Math.floor(seconds / 60);
  if (minutes < 60) return `${minutes}m ago`;
  const hours = Math.floor(minutes / 60);
  if (hours < 24) return `${hours}h ago`;
  return `${Math.floor(hours / 24)}d ago`;
}

export function NotificationBell() {
  const { data: unread } = useUnreadNotificationCount();
  const { data: notifications } = useNotifications();
  const markAsReadMutation = useMarkNotificationAsRead();
  const [isOpen, setIsOpen] = useState(false);

  const count = unread?.count ?? 0;
  const recent = (notifications ?? []).slice(0, 5);

  const onSelect = (notification: AppNotification) => {
    if (!notification.read_at) {
      markAsReadMutation.mutate(notification.id);
    }
    setIsOpen(false);
  };

  return (
    <div className="relative">
      <button
        type="button"
        onClick={() => setIsOpen((v) => !v)}
        className="relative flex h-9 w-9 items-center justify-center rounded-md border border-[#d8cfbd] bg-white text-[#18201f] hover:bg-[#efe8da]"
        aria-label="Notifications"
      >
        <Bell className="h-4 w-4" />
        {count > 0 && (
          <span className="absolute -right-1 -top-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-[#c94f38] px-1 text-[10px] font-bold text-white">
            {count > 9 ? "9+" : count}
          </span>
        )}
      </button>

      {isOpen && (
        <>
          <div className="fixed inset-0 z-10" onClick={() => setIsOpen(false)} />
          <div className="absolute right-0 z-20 mt-2 w-80 rounded-md border border-[#ded7ca] bg-white p-2 shadow-lg">
            <div className="grid gap-1">
              {recent.map((notification) => {
                const content = (
                  <div
                    className={`rounded-md px-3 py-2 text-left text-sm hover:bg-[#efe8da] ${
                      notification.read_at ? "text-[#66746e]" : "font-semibold text-[#18201f]"
                    }`}
                  >
                    <p>{notification.data.title}</p>
                    <p className="mt-0.5 text-xs font-normal text-[#9aa39c]">{timeAgo(notification.created_at)}</p>
                  </div>
                );

                return notification.data.action_url ? (
                  <Link key={notification.id} href={notification.data.action_url} onClick={() => onSelect(notification)}>
                    {content}
                  </Link>
                ) : (
                  <button key={notification.id} type="button" onClick={() => onSelect(notification)}>
                    {content}
                  </button>
                );
              })}
              {recent.length === 0 && <p className="px-3 py-2 text-sm text-[#9aa39c]">No notifications yet.</p>}
            </div>

            <div className="mt-2 border-t border-[#ded7ca] pt-2">
              <Link
                href="/notifications"
                onClick={() => setIsOpen(false)}
                className="block rounded-md px-3 py-2 text-center text-sm font-semibold text-[#12312b] hover:bg-[#efe8da]"
              >
                View all notifications
              </Link>
            </div>
          </div>
        </>
      )}
    </div>
  );
}
