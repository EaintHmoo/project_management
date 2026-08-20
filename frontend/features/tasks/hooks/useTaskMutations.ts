import { useMutation, useQueryClient } from "@tanstack/react-query";
import { assignTask, createTask, deleteTask, moveTask, updateTask } from "@/features/tasks/api/tasks";
import type { CreateTaskInput, TaskStatus, UpdateTaskInput } from "@/features/tasks/types/task";

export function useCreateTask(projectId: number) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (data: CreateTaskInput) => createTask(projectId, data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["tasks", projectId] });
      queryClient.invalidateQueries({ queryKey: ["projects"] });
    },
  });
}

export function useUpdateTask(projectId: number, taskId: number) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (data: UpdateTaskInput) => updateTask(projectId, taskId, data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["tasks", projectId] });
    },
  });
}

export function useMoveTask(projectId: number) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: ({ taskId, status, position }: { taskId: number; status: TaskStatus; position: number }) =>
      moveTask(projectId, taskId, status, position),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["tasks", projectId] });
    },
  });
}

export function useAssignTask(projectId: number) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: ({ taskId, assigneeId }: { taskId: number; assigneeId: number | null }) =>
      assignTask(projectId, taskId, assigneeId),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["tasks", projectId] });
    },
  });
}

export function useDeleteTask(projectId: number) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (taskId: number) => deleteTask(projectId, taskId),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["tasks", projectId] });
      queryClient.invalidateQueries({ queryKey: ["projects"] });
    },
  });
}
