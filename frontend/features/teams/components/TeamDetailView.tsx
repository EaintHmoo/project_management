"use client";

import { useRouter } from "next/navigation";
import { Alert } from "@/components/ui/Alert";
import { Button } from "@/components/ui/Button";
import { Input } from "@/components/ui/Input";
import { useDeleteTeam, useUpdateTeam } from "@/features/teams/hooks/useTeamMutations";
import { useTeam } from "@/features/teams/hooks/useTeams";
import { useCurrentOrganization } from "@/features/organizations";
import { ProjectCard } from "@/features/projects/components/ProjectCard";
import { useProjects } from "@/features/projects/hooks/useProjects";

export function TeamDetailView({ teamId }: { teamId: number }) {
  const router = useRouter();
  const { organizationId, isLoading: isOrganizationLoading } = useCurrentOrganization();
  const { data: team, isLoading, isError } = useTeam(organizationId, teamId);
  const { data: projects } = useProjects(organizationId);
  const updateTeamMutation = useUpdateTeam(organizationId, teamId);
  const deleteTeamMutation = useDeleteTeam(organizationId);

  if (isOrganizationLoading || isLoading) {
    return <p className="text-sm text-[#66746e]">Loading team…</p>;
  }

  if (isError || !team) {
    return <Alert variant="error">Could not load this team.</Alert>;
  }

  const teamProjects = (projects ?? []).filter((project) => project.team_id === team.id);

  const onDelete = () => {
    if (!window.confirm(`Delete "${team.name}"? This cannot be undone.`)) {
      return;
    }

    deleteTeamMutation.mutate(team.id, { onSuccess: () => router.push("/teams") });
  };

  return (
    <div className="grid gap-6">
      <div className="flex flex-wrap items-start justify-between gap-4">
        <div className="min-w-0 flex-1">
          <Input
            defaultValue={team.name}
            className="text-lg font-bold"
            onBlur={(e) => e.target.value !== team.name && updateTeamMutation.mutate({ name: e.target.value })}
          />
          <textarea
            defaultValue={team.description ?? ""}
            placeholder="Add a description…"
            className="mt-2 min-h-[60px] w-full rounded-md border border-[#d8cfbd] bg-white p-3 text-sm outline-none focus:border-[#12312b]"
            onBlur={(e) =>
              e.target.value !== (team.description ?? "") && updateTeamMutation.mutate({ description: e.target.value })
            }
          />
        </div>

        <Button variant="danger" size="sm" isLoading={deleteTeamMutation.isPending} onClick={onDelete}>
          Delete team
        </Button>
      </div>

      <div>
        <h3 className="mb-3 text-lg font-bold">Projects</h3>
        {teamProjects.length > 0 ? (
          <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            {teamProjects.map((project) => (
              <ProjectCard key={project.id} project={project} />
            ))}
          </div>
        ) : (
          <p className="text-sm text-[#9aa39c]">No projects assigned to this team yet.</p>
        )}
      </div>
    </div>
  );
}
