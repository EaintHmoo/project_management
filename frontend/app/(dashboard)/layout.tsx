import { Sidebar } from "@/components/layout/Sidebar";
import { TopNavbar } from "@/components/layout/TopNavbar";
import { AuthGuard } from "@/features/auth";

export default function DashboardLayout({ children }: { children: React.ReactNode }) {
  return (
    <AuthGuard>
      <div className="flex min-h-screen bg-[#f7f5ef] text-[#18201f]">
        <Sidebar />
        <div className="flex min-w-0 flex-1 flex-col">
          <TopNavbar />
          <div className="grid gap-6 px-4 py-6 md:px-8">{children}</div>
        </div>
      </div>
    </AuthGuard>
  );
}
