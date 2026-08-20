import type { Metadata } from "next";
import { ProjectListView } from "@/features/projects";

export const metadata: Metadata = { title: "Projects — Nexus Collaboration" };

export default function ProjectsPage() {
  return <ProjectListView />;
}
