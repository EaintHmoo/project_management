"use client";

import { zodResolver } from "@hookform/resolvers/zod";
import { useState } from "react";
import { useForm } from "react-hook-form";
import { Alert } from "@/components/ui/Alert";
import { Button } from "@/components/ui/Button";
import { FieldError } from "@/components/ui/FieldError";
import { Input } from "@/components/ui/Input";
import { Label } from "@/components/ui/Label";
import { LabelManager } from "@/features/tasks/components/LabelManager";
import { useCreateTask } from "@/features/tasks/hooks/useTaskMutations";
import { createTaskSchema, type CreateTaskFormValues } from "@/features/tasks/schemas/taskSchema";
import { useOrganizationMembers } from "@/features/organizations";
import { ApiError } from "@/lib/api/errors";

export function CreateTaskForm({
  organizationId,
  projectId,
  onCreated,
}: {
  organizationId: string;
  projectId: number;
  onCreated?: () => void;
}) {
  const createTaskMutation = useCreateTask(projectId);
  const { data: members } = useOrganizationMembers(organizationId);
  const [labelIds, setLabelIds] = useState<number[]>([]);
  const {
    register,
    handleSubmit,
    reset,
    formState: { errors },
  } = useForm<CreateTaskFormValues>({ resolver: zodResolver(createTaskSchema) });

  const onSubmit = handleSubmit((values) => {
    createTaskMutation.mutate(
      {
        title: values.title,
        description: values.description,
        priority: values.priority,
        due_at: values.due_at,
        assignee_id: values.assignee_id ? Number(values.assignee_id) : null,
        label_ids: labelIds,
      },
      {
        onSuccess: () => {
          reset();
          setLabelIds([]);
          onCreated?.();
        },
      },
    );
  });

  return (
    <form onSubmit={onSubmit} className="grid gap-4" noValidate>
      {createTaskMutation.isError && (
        <Alert variant="error">
          {createTaskMutation.error instanceof ApiError
            ? createTaskMutation.error.message
            : "Could not create the task."}
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
          <Label htmlFor="priority">Priority</Label>
          <select
            id="priority"
            className="h-11 w-full rounded-md border border-[#d8cfbd] bg-white px-3 text-sm text-[#18201f] outline-none focus:border-[#12312b]"
            defaultValue="medium"
            {...register("priority")}
          >
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
            <option value="urgent">Urgent</option>
          </select>
        </div>

        <div className="grid gap-1.5">
          <Label htmlFor="due_at">Due date</Label>
          <Input id="due_at" type="date" {...register("due_at")} />
        </div>

        <div className="grid gap-1.5">
          <Label htmlFor="assignee_id">Assignee</Label>
          <select
            id="assignee_id"
            className="h-11 w-full rounded-md border border-[#d8cfbd] bg-white px-3 text-sm text-[#18201f] outline-none focus:border-[#12312b]"
            defaultValue=""
            {...register("assignee_id")}
          >
            <option value="">Unassigned</option>
            {(members ?? []).map((member) => (
              <option key={member.user.id} value={member.user.id}>
                {member.user.name}
              </option>
            ))}
          </select>
        </div>
      </div>

      <div className="grid gap-1.5">
        <Label>Labels</Label>
        <LabelManager
          organizationId={organizationId}
          selectedIds={labelIds}
          onToggle={(id) =>
            setLabelIds((current) => (current.includes(id) ? current.filter((v) => v !== id) : [...current, id]))
          }
        />
      </div>

      <Button type="submit" isLoading={createTaskMutation.isPending} className="justify-self-start">
        Create task
      </Button>
    </form>
  );
}
