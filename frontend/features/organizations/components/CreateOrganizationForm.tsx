"use client";

import { zodResolver } from "@hookform/resolvers/zod";
import { useForm } from "react-hook-form";
import { Alert } from "@/components/ui/Alert";
import { Button } from "@/components/ui/Button";
import { FieldError } from "@/components/ui/FieldError";
import { Input } from "@/components/ui/Input";
import { Label } from "@/components/ui/Label";
import { useCreateOrganization } from "@/features/organizations/hooks/useOrganizationMutations";
import { createOrganizationSchema, type CreateOrganizationFormValues } from "@/features/organizations/schemas/organizationSchema";
import { ApiError } from "@/lib/api/errors";

export function CreateOrganizationForm({ onCreated }: { onCreated?: () => void }) {
  const createOrganizationMutation = useCreateOrganization();
  const {
    register,
    handleSubmit,
    reset,
    formState: { errors },
  } = useForm<CreateOrganizationFormValues>({ resolver: zodResolver(createOrganizationSchema) });

  const onSubmit = handleSubmit((values) => {
    createOrganizationMutation.mutate(
      { name: values.name, slug: values.slug || undefined, timezone: values.timezone || undefined },
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
      {createOrganizationMutation.isError && (
        <Alert variant="error">
          {createOrganizationMutation.error instanceof ApiError
            ? createOrganizationMutation.error.message
            : "Could not create the organization."}
        </Alert>
      )}

      <div className="grid gap-1.5">
        <Label htmlFor="org-name">Name</Label>
        <Input id="org-name" {...register("name")} />
        <FieldError message={errors.name?.message} />
      </div>

      <div className="grid gap-4 sm:grid-cols-2">
        <div className="grid gap-1.5">
          <Label htmlFor="org-slug">Slug (optional)</Label>
          <Input id="org-slug" placeholder="acme-inc" {...register("slug")} />
          <FieldError message={errors.slug?.message} />
        </div>

        <div className="grid gap-1.5">
          <Label htmlFor="org-timezone">Timezone</Label>
          <Input
            id="org-timezone"
            placeholder={Intl.DateTimeFormat().resolvedOptions().timeZone}
            {...register("timezone")}
          />
        </div>
      </div>

      <Button type="submit" isLoading={createOrganizationMutation.isPending} className="justify-self-start">
        Create organization
      </Button>
    </form>
  );
}
