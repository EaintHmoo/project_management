import type { Metadata } from "next";
import { ProjectDetailView } from "@/features/projects";

export const metadata: Metadata = { title: "Project — Nexus Collaboration" };

export default async function ProjectDetailPage({
  params,
}: {
  params: Promise<{ projectId: string }>;
}) {
  const { projectId } = await params;

  return <ProjectDetailView projectId={Number(projectId)} />;
}
