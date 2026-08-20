import { apiClient } from "@/lib/api/client";
import type { CreateTaskInput, Task, TaskBoard, TaskStatus, UpdateTaskInput } from "@/features/tasks/types/task";

export function listTasks(projectId: number): Promise<TaskBoard> {
  return apiClient.get<TaskBoard>(`/projects/${projectId}/tasks`);
}

export function getTask(projectId: number, taskId: number): Promise<Task> {
  return apiClient.get<Task>(`/projects/${projectId}/tasks/${taskId}`);
}

export function createTask(projectId: number, data: CreateTaskInput): Promise<Task> {
  return apiClient.post<Task>(`/projects/${projectId}/tasks`, data);
}

export function updateTask(projectId: number, taskId: number, data: UpdateTaskInput): Promise<Task> {
  return apiClient.patch<Task>(`/projects/${projectId}/tasks/${taskId}`, data);
}

export function moveTask(
  projectId: number,
  taskId: number,
  status: TaskStatus,
  position: number,
): Promise<Task> {
  return apiClient.post<Task>(`/projects/${projectId}/tasks/${taskId}/move`, { status, position });
}

export function assignTask(projectId: number, taskId: number, assigneeId: number | null): Promise<Task> {
  return apiClient.post<Task>(`/projects/${projectId}/tasks/${taskId}/assign`, { assignee_id: assigneeId });
}

export function deleteTask(projectId: number, taskId: number): Promise<null> {
  return apiClient.delete<null>(`/projects/${projectId}/tasks/${taskId}`);
}
