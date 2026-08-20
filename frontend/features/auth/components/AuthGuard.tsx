"use client";

import { useRouter } from "next/navigation";
import { useEffect } from "react";
import { useAuthStore } from "@/stores/authStore";

export function AuthGuard({ children }: { children: React.ReactNode }) {
  const router = useRouter();
  const isHydrated = useAuthStore((state) => state.isHydrated);
  const token = useAuthStore((state) => state.token);

  useEffect(() => {
    if (isHydrated && !token) {
      router.replace("/login");
    }
  }, [isHydrated, token, router]);

  if (!isHydrated || !token) {
    return (
      <div className="flex min-h-screen items-center justify-center bg-[#f7f5ef] text-sm text-[#66746e]">
        Loading…
      </div>
    );
  }

  return <>{children}</>;
}
