"use client";

import { zodResolver } from "@hookform/resolvers/zod";
import { useForm } from "react-hook-form";
import { Alert } from "@/components/ui/Alert";
import { Button } from "@/components/ui/Button";
import { FieldError } from "@/components/ui/FieldError";
import { Input } from "@/components/ui/Input";
import { Label } from "@/components/ui/Label";
import { useCreateTeam } from "@/features/teams/hooks/useTeamMutations";
import { createTeamSchema, type CreateTeamFormValues } from "@/features/teams/schemas/teamSchema";
import { ApiError } from "@/lib/api/errors";

export function CreateTeamForm({ organizationId, onCreated }: { organizationId: string; onCreated?: () => void }) {
  const createTeamMutation = useCreateTeam(organizationId);
  const {
    register,
    handleSubmit,
    reset,
    formState: { errors },
  } = useForm<CreateTeamFormValues>({ resolver: zodResolver(createTeamSchema) });

  const onSubmit = handleSubmit((values) => {
    createTeamMutation.mutate(values, {
      onSuccess: () => {
        reset();
        onCreated?.();
      },
    });
  });

  return (
    <form onSubmit={onSubmit} className="grid gap-4" noValidate>
      {createTeamMutation.isError && (
        <Alert variant="error">
          {createTeamMutation.error instanceof ApiError ? createTeamMutation.error.message : "Could not create the team."}
        </Alert>
      )}

      <div className="grid gap-1.5">
        <Label htmlFor="team-name">Name</Label>
        <Input id="team-name" {...register("name")} />
        <FieldError message={errors.name?.message} />
      </div>

      <div className="grid gap-1.5">
        <Label htmlFor="team-description">Description</Label>
        <Input id="team-description" {...register("description")} />
      </div>

      <Button type="submit" isLoading={createTeamMutation.isPending} className="justify-self-start">
        Create team
      </Button>
    </form>
  );
}
