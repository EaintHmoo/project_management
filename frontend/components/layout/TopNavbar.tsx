"use client";

import { useRouter } from "next/navigation";
import { Button } from "@/components/ui/Button";
import { useLogout } from "@/features/auth";
import { useAuthStore } from "@/stores/authStore";

export function TopNavbar() {
  const router = useRouter();
  const user = useAuthStore((state) => state.user);
  const logoutMutation = useLogout();

  const onSignOut = () => {
    logoutMutation.mutate(undefined, {
      onSettled: () => router.push("/login"),
    });
  };

  return (
    <header className="border-b border-[#ded7ca] bg-[#fffcf4]/90 px-4 py-4 backdrop-blur md:px-8">
      <div className="flex items-center justify-between gap-4">
        <div>
          <p className="text-sm font-semibold text-[#66746e]">Multi-tenant SaaS workspace</p>
          <h2 className="mt-1 text-2xl font-bold text-[#18201f]">Project command center</h2>
        </div>
        <div className="flex items-center gap-3">
          {user && <span className="hidden text-sm font-medium text-[#4f5f58] sm:inline">{user.name}</span>}
          <Button variant="secondary" size="sm" isLoading={logoutMutation.isPending} onClick={onSignOut}>
            Sign out
          </Button>
        </div>
      </div>
    </header>
  );
}
