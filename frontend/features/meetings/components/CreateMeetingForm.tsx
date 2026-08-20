"use client";

import { zodResolver } from "@hookform/resolvers/zod";
import { useState } from "react";
import { useForm } from "react-hook-form";
import { Alert } from "@/components/ui/Alert";
import { Button } from "@/components/ui/Button";
import { FieldError } from "@/components/ui/FieldError";
import { Input } from "@/components/ui/Input";
import { Label } from "@/components/ui/Label";
import { useCreateMeeting } from "@/features/meetings/hooks/useMeetingMutations";
import { createMeetingSchema, type CreateMeetingFormValues } from "@/features/meetings/schemas/meetingSchema";
import { useOrganizationMembers } from "@/features/organizations";
import { ApiError } from "@/lib/api/errors";

export function CreateMeetingForm({
  organizationId,
  onCreated,
}: {
  organizationId: string;
  onCreated?: () => void;
}) {
  const createMeetingMutation = useCreateMeeting(organizationId);
  const { data: members } = useOrganizationMembers(organizationId);
  const [participantIds, setParticipantIds] = useState<number[]>([]);
  const {
    register,
    handleSubmit,
    reset,
    formState: { errors },
  } = useForm<CreateMeetingFormValues>({
    resolver: zodResolver(createMeetingSchema),
    defaultValues: { timezone: Intl.DateTimeFormat().resolvedOptions().timeZone },
  });

  const onSubmit = handleSubmit((values) => {
    createMeetingMutation.mutate(
      { ...values, participant_ids: participantIds },
      {
        onSuccess: () => {
          reset();
          setParticipantIds([]);
          onCreated?.();
        },
      },
    );
  });

  return (
    <form onSubmit={onSubmit} className="grid gap-4" noValidate>
      {createMeetingMutation.isError && (
        <Alert variant="error">
          {createMeetingMutation.error instanceof ApiError
            ? createMeetingMutation.error.message
            : "Could not schedule the meeting."}
        </Alert>
      )}

      <div className="grid gap-1.5">
        <Label htmlFor="title">Title</Label>
        <Input id="title" {...register("title")} />
        <FieldError message={errors.title?.message} />
      </div>

      <div className="grid gap-1.5">
        <Label htmlFor="description">Description</Label>
        <Input id="description" {...register("description")} />
      </div>

      <div className="grid gap-4 sm:grid-cols-3">
        <div className="grid gap-1.5">
          <Label htmlFor="starts_at">Starts at</Label>
          <Input id="starts_at" type="datetime-local" {...register("starts_at")} />
          <FieldError message={errors.starts_at?.message} />
        </div>

        <div className="grid gap-1.5">
          <Label htmlFor="ends_at">Ends at</Label>
          <Input id="ends_at" type="datetime-local" {...register("ends_at")} />
          <FieldError message={errors.ends_at?.message} />
        </div>

        <div className="grid gap-1.5">
          <Label htmlFor="timezone">Timezone</Label>
          <Input id="timezone" {...register("timezone")} />
          <FieldError message={errors.timezone?.message} />
        </div>
      </div>

      <div className="grid gap-1.5">
        <Label>Participants</Label>
        <div className="flex flex-wrap gap-2">
          {(members ?? []).map((member) => {
            const isSelected = participantIds.includes(member.user.id);

            return (
              <button
                key={member.user.id}
                type="button"
                onClick={() =>
                  setParticipantIds((current) =>
                    isSelected ? current.filter((id) => id !== member.user.id) : [...current, member.user.id],
                  )
                }
                className={`rounded-md border px-2 py-1 text-xs font-semibold transition-colors ${
                  isSelected ? "border-[#12312b] bg-[#12312b] text-white" : "border-[#d8cfbd] bg-white text-[#4f5f58]"
                }`}
              >
                {member.user.name}
              </button>
            );
          })}
        </div>
      </div>

      <Button type="submit" isLoading={createMeetingMutation.isPending} className="justify-self-start">
        Schedule meeting
      </Button>
    </form>
  );
}
