"use client";

import { Alert } from "@/components/ui/Alert";
import { KanbanBoard } from "@/features/tasks";
import { ProjectDetailHeader } from "@/features/projects/components/ProjectDetailHeader";
import { useProject } from "@/features/projects/hooks/useProjects";
import { useCurrentOrganization } from "@/features/organizations";

export function ProjectDetailView({ projectId }: { projectId: number }) {
  const { organizationId, isLoading: isOrganizationLoading } = useCurrentOrganization();
  const { data: project, isLoading, isError } = useProject(organizationId, projectId);

  if (isOrganizationLoading || isLoading) {
    return <p className="text-sm text-[#66746e]">Loading project…</p>;
  }

  if (isError || !project || !organizationId) {
    return <Alert variant="error">Could not load this project.</Alert>;
  }

  return (
    <div className="grid gap-6">
      <ProjectDetailHeader organizationId={organizationId} project={project} />
      <KanbanBoard organizationId={organizationId} projectId={project.id} />
    </div>
  );
}
