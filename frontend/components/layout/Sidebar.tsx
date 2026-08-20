"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";

const NAV_ITEMS = [
  { label: "Dashboard", href: "/dashboard" },
  { label: "Notifications", href: "/notifications" },
  { label: "Projects", href: "/projects" },
  { label: "Teams", href: "/teams" },
  { label: "Meetings", href: "/meetings" },
  { label: "Invitations", href: "/invitations" },
  { label: "Files", href: "#" },
  { label: "Analytics", href: "#" },
  { label: "Settings", href: "/settings/organization" },
];

export function Sidebar() {
  const pathname = usePathname();

  return (
    <aside className="hidden w-64 shrink-0 border-r border-[#ded7ca] bg-[#fffcf4] px-5 py-6 lg:block">
      <div className="mb-8">
        <p className="text-xs font-semibold uppercase tracking-[0.18em] text-[#66746e]">Nexus</p>
        <h1 className="mt-2 text-2xl font-bold text-[#18201f]">Collaboration</h1>
      </div>

      <nav className="grid gap-1 text-sm font-medium text-[#4f5f58]">
        {NAV_ITEMS.map((item) => {
          const isActive = item.href !== "#" && (pathname === item.href || pathname.startsWith(`${item.href}/`));
          const isDisabled = item.href === "#";

          return (
            <Link
              key={item.label}
              href={item.href}
              aria-disabled={isDisabled}
              className={`rounded-md px-3 py-2 ${
                isActive
                  ? "bg-[#12312b] text-white"
                  : isDisabled
                    ? "cursor-not-allowed text-[#a6ada5]"
                    : "hover:bg-[#efe8da]"
              }`}
              onClick={(event) => isDisabled && event.preventDefault()}
            >
              {item.label}
            </Link>
          );
        })}
      </nav>
    </aside>
  );
}
