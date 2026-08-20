"use client";

import { useRouter } from "next/navigation";
import { Button } from "@/components/ui/Button";
import { useArchiveProject, useDeleteProject } from "@/features/projects/hooks/useProjectMutations";
import type { Project } from "@/features/projects/types/project";

const STATUS_LABELS: Record<Project["status"], string> = {
  planning: "Planning",
  active: "Active",
  on_hold: "On hold",
  completed: "Completed",
  archived: "Archived",
};

export function ProjectDetailHeader({
  organizationId,
  project,
}: {
  organizationId: string;
  project: Project;
}) {
  const router = useRouter();
  const archiveMutation = useArchiveProject(organizationId, project.id);
  const deleteMutation = useDeleteProject(organizationId);

  const onDelete = () => {
    if (!window.confirm(`Delete "${project.name}"? This cannot be undone.`)) {
      return;
    }

    deleteMutation.mutate(project.id, {
      onSuccess: () => router.push("/projects"),
    });
  };

  return (
    <div className="flex flex-wrap items-start justify-between gap-4">
      <div>
        <div className="flex items-center gap-2">
          <span className="rounded-md bg-[#ece3d2] px-2 py-1 text-xs font-semibold uppercase tracking-wide text-[#4f5f58]">
            {project.key}
          </span>
          <span className="text-xs font-medium text-[#66746e]">{STATUS_LABELS[project.status]}</span>
        </div>
        <h2 className="mt-2 text-2xl font-bold text-[#18201f]">{project.name}</h2>
        {project.description && <p className="mt-1 text-sm text-[#66746e]">{project.description}</p>}
      </div>

      <div className="flex items-center gap-2">
        {project.status !== "archived" && (
          <Button
            variant="secondary"
            size="sm"
            isLoading={archiveMutation.isPending}
            onClick={() => archiveMutation.mutate()}
          >
            Archive
          </Button>
        )}
        <Button variant="danger" size="sm" isLoading={deleteMutation.isPending} onClick={onDelete}>
          Delete
        </Button>
      </div>
    </div>
  );
}
