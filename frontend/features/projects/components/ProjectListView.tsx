"use client";

import { useState } from "react";
import { Button } from "@/components/ui/Button";
import { Card } from "@/components/ui/Card";
import { CreateProjectForm } from "@/features/projects/components/CreateProjectForm";
import { ProjectCard } from "@/features/projects/components/ProjectCard";
import { useProjects } from "@/features/projects/hooks/useProjects";
import { useCurrentOrganization } from "@/features/organizations";

export function ProjectListView() {
  const { organizationId, isLoading: isOrganizationLoading } = useCurrentOrganization();
  const { data: projects, isLoading, isError } = useProjects(organizationId);
  const [isCreating, setIsCreating] = useState(false);

  if (isOrganizationLoading || isLoading) {
    return <p className="text-sm text-[#66746e]">Loading projects…</p>;
  }

  if (!organizationId) {
    return (
      <Card>
        <h3 className="text-lg font-bold">No workspace yet</h3>
        <p className="mt-2 text-sm text-[#66746e]">
          Create an organization to get started with projects.
        </p>
      </Card>
    );
  }

  if (isError) {
    return (
      <Card className="border-[#f4c9c0] bg-[#f4e7e3] text-[#8f321f]">
        Could not load projects. Is the API running?
      </Card>
    );
  }

  return (
    <div className="grid gap-6">
      <div className="flex items-center justify-between">
        <h2 className="text-xl font-bold">Projects</h2>
        <Button size="sm" variant={isCreating ? "secondary" : "primary"} onClick={() => setIsCreating((v) => !v)}>
          {isCreating ? "Cancel" : "New project"}
        </Button>
      </div>

      {isCreating && (
        <Card>
          <CreateProjectForm organizationId={organizationId} onCreated={() => setIsCreating(false)} />
        </Card>
      )}

      {projects && projects.length > 0 ? (
        <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
          {projects.map((project) => (
            <ProjectCard key={project.id} project={project} />
          ))}
        </div>
      ) : (
        <Card>
          <p className="text-sm text-[#66746e]">No projects yet. Create your first one above.</p>
        </Card>
      )}
    </div>
  );
}
