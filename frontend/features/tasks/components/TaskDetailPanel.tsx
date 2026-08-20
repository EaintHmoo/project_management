"use client";

import { zodResolver } from "@hookform/resolvers/zod";
import { useState } from "react";
import { useForm } from "react-hook-form";
import { Button } from "@/components/ui/Button";
import { Input } from "@/components/ui/Input";
import { Label } from "@/components/ui/Label";
import { LabelManager } from "@/features/tasks/components/LabelManager";
import { useComments, useCreateComment, useDeleteComment } from "@/features/tasks/hooks/useComments";
import { useAssignTask, useDeleteTask, useUpdateTask } from "@/features/tasks/hooks/useTaskMutations";
import { createCommentSchema, type CreateCommentFormValues } from "@/features/tasks/schemas/commentSchema";
import type { Task } from "@/features/tasks/types/task";
import { useOrganizationMembers } from "@/features/organizations";
import { useAuthStore } from "@/stores/authStore";

export function TaskDetailPanel({
  organizationId,
  projectId,
  task,
  onClose,
}: {
  organizationId: string;
  projectId: number;
  task: Task;
  onClose: () => void;
}) {
  const currentUser = useAuthStore((state) => state.user);
  const { data: members } = useOrganizationMembers(organizationId);
  const updateTaskMutation = useUpdateTask(projectId, task.id);
  const assignTaskMutation = useAssignTask(projectId);
  const deleteTaskMutation = useDeleteTask(projectId);
  const { data: comments } = useComments(task.id);
  const createCommentMutation = useCreateComment(task.id);
  const deleteCommentMutation = useDeleteComment(task.id);
  const [labelIds, setLabelIds] = useState<number[]>(task.labels.map((l) => l.id));

  const {
    register,
    handleSubmit,
    reset,
  } = useForm<CreateCommentFormValues>({ resolver: zodResolver(createCommentSchema) });

  const onToggleLabel = (labelId: number) => {
    const next = labelIds.includes(labelId) ? labelIds.filter((id) => id !== labelId) : [...labelIds, labelId];
    setLabelIds(next);
    updateTaskMutation.mutate({ label_ids: next });
  };

  const onDelete = () => {
    if (!window.confirm(`Delete "${task.title}"?`)) {
      return;
    }

    deleteTaskMutation.mutate(task.id, { onSuccess: onClose });
  };

  const onSubmitComment = handleSubmit((values) => {
    createCommentMutation.mutate({ body: values.body }, { onSuccess: () => reset() });
  });

  return (
    <div className="fixed inset-0 z-50 flex justify-end bg-black/30" onClick={onClose}>
      <div
        className="grid h-full w-full max-w-lg gap-6 overflow-y-auto bg-[#fffcf4] p-6 shadow-xl"
        onClick={(event) => event.stopPropagation()}
      >
        <div className="flex items-start justify-between gap-4">
          <Input
            defaultValue={task.title}
            className="text-lg font-bold"
            onBlur={(e) => e.target.value !== task.title && updateTaskMutation.mutate({ title: e.target.value })}
          />
          <Button variant="ghost" size="sm" onClick={onClose}>
            ✕
          </Button>
        </div>

        <textarea
          defaultValue={task.description ?? ""}
          placeholder="Add a description…"
          className="min-h-[80px] w-full rounded-md border border-[#d8cfbd] bg-white p-3 text-sm outline-none focus:border-[#12312b]"
          onBlur={(e) =>
            e.target.value !== (task.description ?? "") && updateTaskMutation.mutate({ description: e.target.value })
          }
        />

        <div className="grid grid-cols-3 gap-4">
          <div className="grid gap-1.5">
            <Label>Status</Label>
            <select
              defaultValue={task.status}
              className="h-10 w-full rounded-md border border-[#d8cfbd] bg-white px-2 text-sm outline-none focus:border-[#12312b]"
              onChange={(e) => updateTaskMutation.mutate({ status: e.target.value as Task["status"] })}
            >
              <option value="todo">Todo</option>
              <option value="in_progress">In Progress</option>
              <option value="review">Review</option>
              <option value="done">Done</option>
            </select>
          </div>

          <div className="grid gap-1.5">
            <Label>Priority</Label>
            <select
              defaultValue={task.priority}
              className="h-10 w-full rounded-md border border-[#d8cfbd] bg-white px-2 text-sm outline-none focus:border-[#12312b]"
              onChange={(e) => updateTaskMutation.mutate({ priority: e.target.value as Task["priority"] })}
            >
              <option value="low">Low</option>
              <option value="medium">Medium</option>
              <option value="high">High</option>
              <option value="urgent">Urgent</option>
            </select>
          </div>

          <div className="grid gap-1.5">
            <Label>Assignee</Label>
            <select
              defaultValue={task.assignee?.id ?? ""}
              className="h-10 w-full rounded-md border border-[#d8cfbd] bg-white px-2 text-sm outline-none focus:border-[#12312b]"
              onChange={(e) =>
                assignTaskMutation.mutate({ taskId: task.id, assigneeId: e.target.value ? Number(e.target.value) : null })
              }
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
          <Label htmlFor="due_at">Due date</Label>
          <Input
            id="due_at"
            type="date"
            defaultValue={task.due_at ? task.due_at.slice(0, 10) : ""}
            onChange={(e) => updateTaskMutation.mutate({ due_at: e.target.value || null })}
          />
        </div>

        <div className="grid gap-1.5">
          <Label>Labels</Label>
          <LabelManager organizationId={organizationId} selectedIds={labelIds} onToggle={onToggleLabel} />
        </div>

        <Button variant="danger" size="sm" isLoading={deleteTaskMutation.isPending} onClick={onDelete} className="justify-self-start">
          Delete task
        </Button>

        <div className="grid gap-3 border-t border-[#ded7ca] pt-4">
          <h4 className="font-bold">Comments</h4>

          <form onSubmit={onSubmitComment} className="flex items-start gap-2" noValidate>
            <Input placeholder="Write a comment…" {...register("body")} />
            <Button type="submit" size="sm" isLoading={createCommentMutation.isPending}>
              Post
            </Button>
          </form>

          <div className="grid gap-3">
            {(comments ?? []).map((comment) => (
              <div key={comment.id} className="rounded-md border border-[#ded7ca] bg-white p-3 text-sm">
                <div className="flex items-center justify-between">
                  <span className="font-semibold">{comment.author.name}</span>
                  <div className="flex items-center gap-2 text-xs text-[#9aa39c]">
                    <span>{new Date(comment.created_at).toLocaleString()}</span>
                    {comment.author.id === currentUser?.id && (
                      <button
                        type="button"
                        className="hover:text-[#c94f38]"
                        onClick={() => deleteCommentMutation.mutate(comment.id)}
                      >
                        Delete
                      </button>
                    )}
                  </div>
                </div>
                <p className="mt-1 text-[#3d4440]">{comment.body}</p>
              </div>
            ))}
            {comments && comments.length === 0 && <p className="text-xs text-[#9aa39c]">No comments yet.</p>}
          </div>
        </div>
      </div>
    </div>
  );
}
