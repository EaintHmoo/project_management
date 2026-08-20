import type { Metadata } from "next";
import { TeamDetailView } from "@/features/teams";

export const metadata: Metadata = { title: "Team — Nexus Collaboration" };

export default async function TeamDetailPage({
  params,
}: {
  params: Promise<{ teamId: string }>;
}) {
  const { teamId } = await params;

  return <TeamDetailView teamId={Number(teamId)} />;
}
