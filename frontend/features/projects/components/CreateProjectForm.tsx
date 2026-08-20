"use client";

import { zodResolver } from "@hookform/resolvers/zod";
import { useForm } from "react-hook-form";
import { Alert } from "@/components/ui/Alert";
import { Button } from "@/components/ui/Button";
import { FieldError } from "@/components/ui/FieldError";
import { Input } from "@/components/ui/Input";
import { Label } from "@/components/ui/Label";
import { useCreateProject } from "@/features/projects/hooks/useProjectMutations";
import { createProjectSchema, type CreateProjectFormValues } from "@/features/projects/schemas/projectSchema";
import { useTeams } from "@/features/teams/hooks/useTeams";
import { ApiError } from "@/lib/api/errors";

export function CreateProjectForm({
  organizationId,
  onCreated,
}: {
  organizationId: string;
  onCreated?: () => void;
}) {
  const createProjectMutation = useCreateProject(organizationId);
  const { data: teams } = useTeams(organizationId);
  const {
    register,
    handleSubmit,
    reset,
    formState: { errors },
  } = useForm<CreateProjectFormValues>({ resolver: zodResolver(createProjectSchema) });

  const onSubmit = handleSubmit((values) => {
    createProjectMutation.mutate(
      { ...values, team_id: values.team_id ? Number(values.team_id) : null },
      {
        onSuccess: () => {
          reset();
          onCreated?.();
        },
      },
    );
  });

  return (
    <form onSubmit={onSubmit} className="grid gap-4" noValidate>
      {createProjectMutation.isError && (
        <Alert variant="error">
          {createProjectMutation.error instanceof ApiError
            ? createProjectMutation.error.message
            : "Could not create the project."}
        </Alert>
      )}

      <div className="grid gap-4 sm:grid-cols-[1fr_140px]">
        <div className="grid gap-1.5">
          <Label htmlFor="name">Name</Label>
          <Input id="name" {...register("name")} />
          <FieldError message={errors.name?.message} />
        </div>

        <div className="grid gap-1.5">
          <Label htmlFor="key">Key</Label>
          <Input id="key" placeholder="ENG" {...register("key")} />
          <FieldError message={errors.key?.message} />
        </div>
      </div>

      <div className="grid gap-1.5">
        <Label htmlFor="description">Description</Label>
        <Input id="description" {...register("description")} />
        <FieldError message={errors.description?.message} />
      </div>

      <div className="grid gap-4 sm:grid-cols-2">
        <div className="grid gap-1.5">
          <Label htmlFor="visibility">Visibility</Label>
          <select
            id="visibility"
            className="h-11 w-full rounded-md border border-[#d8cfbd] bg-white px-3 text-sm text-[#18201f] outline-none focus:border-[#12312b]"
            defaultValue="organization"
            {...register("visibility")}
          >
            <option value="organization">Organization</option>
            <option value="team">Team</option>
            <option value="private">Private</option>
          </select>
        </div>

        <div className="grid gap-1.5">
          <Label htmlFor="team_id">Team (optional)</Label>
          <select
            id="team_id"
            className="h-11 w-full rounded-md border border-[#d8cfbd] bg-white px-3 text-sm text-[#18201f] outline-none focus:border-[#12312b]"
            defaultValue=""
            {...register("team_id")}
          >
            <option value="">No team</option>
            {(teams ?? []).map((team) => (
              <option key={team.id} value={team.id}>
                {team.name}
              </option>
            ))}
          </select>
        </div>
      </div>

      <div className="grid gap-4 sm:grid-cols-2">
        <div className="grid gap-1.5">
          <Label htmlFor="starts_at">Start date</Label>
          <Input id="starts_at" type="date" {...register("starts_at")} />
        </div>

        <div className="grid gap-1.5">
          <Label htmlFor="ends_at">End date</Label>
          <Input id="ends_at" type="date" {...register("ends_at")} />
          <FieldError message={errors.ends_at?.message} />
        </div>
      </div>

      <Button type="submit" isLoading={createProjectMutation.isPending} className="justify-self-start">
        Create project
      </Button>
    </form>
  );
}
