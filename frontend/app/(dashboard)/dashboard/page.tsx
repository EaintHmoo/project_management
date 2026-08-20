import type { Metadata } from "next";
import { WorkspaceOverviewView } from "@/features/dashboard/components/WorkspaceOverviewView";

export const metadata: Metadata = { title: "Dashboard — Nexus Collaboration" };

export default function DashboardPage() {
  return <WorkspaceOverviewView />;
}
