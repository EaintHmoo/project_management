"use client";

import { useState } from "react";
import { Button } from "@/components/ui/Button";
import { Card } from "@/components/ui/Card";
import { CreateTeamForm } from "@/features/teams/components/CreateTeamForm";
import { TeamCard } from "@/features/teams/components/TeamCard";
import { useTeams } from "@/features/teams/hooks/useTeams";
import { useCurrentOrganization } from "@/features/organizations";

export function TeamListView() {
  const { organizationId, isLoading: isOrganizationLoading } = useCurrentOrganization();
  const { data: teams, isLoading, isError } = useTeams(organizationId);
  const [isCreating, setIsCreating] = useState(false);

  if (isOrganizationLoading || isLoading) {
    return <p className="text-sm text-[#66746e]">Loading teams…</p>;
  }

  if (!organizationId) {
    return (
      <Card>
        <h3 className="text-lg font-bold">No organization yet</h3>
        <p className="mt-2 text-sm text-[#66746e]">Create an organization to get started with teams.</p>
      </Card>
    );
  }

  if (isError) {
    return (
      <Card className="border-[#f4c9c0] bg-[#f4e7e3] text-[#8f321f]">Could not load teams. Is the API running?</Card>
    );
  }

  return (
    <div className="grid gap-6">
      <div className="flex items-center justify-between">
        <h2 className="text-xl font-bold">Teams</h2>
        <Button size="sm" variant={isCreating ? "secondary" : "primary"} onClick={() => setIsCreating((v) => !v)}>
          {isCreating ? "Cancel" : "New team"}
        </Button>
      </div>

      {isCreating && (
        <Card>
          <CreateTeamForm organizationId={organizationId} onCreated={() => setIsCreating(false)} />
        </Card>
      )}

      {teams && teams.length > 0 ? (
        <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
          {teams.map((team) => (
            <TeamCard key={team.id} team={team} />
          ))}
        </div>
      ) : (
        <Card>
          <p className="text-sm text-[#66746e]">No teams yet. Create your first one above.</p>
        </Card>
      )}
    </div>
  );
}
