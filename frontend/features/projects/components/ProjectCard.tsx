import Link from "next/link";
import { Card } from "@/components/ui/Card";
import type { Project } from "@/features/projects/types/project";

const STATUS_LABELS: Record<Project["status"], string> = {
  planning: "Planning",
  active: "Active",
  on_hold: "On hold",
  completed: "Completed",
  archived: "Archived",
};

export function ProjectCard({ project }: { project: Project }) {
  return (
    <Link href={`/projects/${project.id}`}>
      <Card className="flex h-full flex-col justify-between transition-colors hover:border-[#12312b]">
        <div>
          <div className="flex items-center justify-between gap-2">
            <span className="rounded-md bg-[#ece3d2] px-2 py-1 text-xs font-semibold uppercase tracking-wide text-[#4f5f58]">
              {project.key}
            </span>
            <span className="text-xs font-medium text-[#66746e]">{STATUS_LABELS[project.status]}</span>
          </div>
          <h3 className="mt-3 text-lg font-bold text-[#18201f]">{project.name}</h3>
          {project.description && (
            <p className="mt-1 line-clamp-2 text-sm text-[#66746e]">{project.description}</p>
          )}
        </div>
        <p className="mt-4 text-xs font-medium text-[#9aa39c]">
          {project.tasks_count ?? 0} task{project.tasks_count === 1 ? "" : "s"}
        </p>
      </Card>
    </Link>
  );
}
