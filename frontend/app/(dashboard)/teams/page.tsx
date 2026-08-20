import type { Metadata } from "next";
import { TeamListView } from "@/features/teams";

export const metadata: Metadata = { title: "Teams — Nexus Collaboration" };

export default function TeamsPage() {
  return <TeamListView />;
}
