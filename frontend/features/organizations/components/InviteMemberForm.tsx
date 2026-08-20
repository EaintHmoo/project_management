"use client";

import { zodResolver } from "@hookform/resolvers/zod";
import { useForm } from "react-hook-form";
import { Alert } from "@/components/ui/Alert";
import { Button } from "@/components/ui/Button";
import { FieldError } from "@/components/ui/FieldError";
import { Input } from "@/components/ui/Input";
import { Label } from "@/components/ui/Label";
import { useInviteMember } from "@/features/organizations/hooks/useInvitations";
import { inviteMemberSchema, type InviteMemberFormValues } from "@/features/organizations/schemas/invitationSchema";
import { ORGANIZATION_ROLES } from "@/features/organizations/types/organization";
import { ApiError } from "@/lib/api/errors";

export function InviteMemberForm({ organizationId }: { organizationId: string }) {
  const inviteMemberMutation = useInviteMember(organizationId);
  const {
    register,
    handleSubmit,
    reset,
    formState: { errors },
  } = useForm<InviteMemberFormValues>({ resolver: zodResolver(inviteMemberSchema), defaultValues: { role: "member" } });

  const onSubmit = handleSubmit((values) => {
    inviteMemberMutation.mutate(values, { onSuccess: () => reset() });
  });

  return (
    <form onSubmit={onSubmit} className="grid gap-3 sm:grid-cols-[1fr_140px_auto] sm:items-end" noValidate>
      {inviteMemberMutation.isError && (
        <Alert variant="error" className="sm:col-span-3">
          {inviteMemberMutation.error instanceof ApiError
            ? inviteMemberMutation.error.message
            : "Could not send the invitation."}
        </Alert>
      )}

      <div className="grid gap-1.5">
        <Label htmlFor="invite-email">Email</Label>
        <Input id="invite-email" type="email" placeholder="teammate@company.com" {...register("email")} />
        <FieldError message={errors.email?.message} />
      </div>

      <div className="grid gap-1.5">
        <Label htmlFor="invite-role">Role</Label>
        <select
          id="invite-role"
          className="h-11 w-full rounded-md border border-[#d8cfbd] bg-white px-3 text-sm text-[#18201f] outline-none focus:border-[#12312b]"
          {...register("role")}
        >
          {ORGANIZATION_ROLES.filter((role) => role !== "owner").map((role) => (
            <option key={role} value={role}>
              {role}
            </option>
          ))}
        </select>
      </div>

      <Button type="submit" isLoading={inviteMemberMutation.isPending}>
        Invite
      </Button>
    </form>
  );
}
