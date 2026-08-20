import { apiClient } from "@/lib/api/client";
import type { Comment } from "@/features/tasks/types/task";

export function listComments(taskId: number): Promise<Comment[]> {
  return apiClient.get<Comment[]>(`/tasks/${taskId}/comments`);
}

export function createComment(
  taskId: number,
  body: string,
  mentions: number[] = [],
  parentId: number | null = null,
): Promise<Comment> {
  return apiClient.post<Comment>(`/tasks/${taskId}/comments`, { body, mentions, parent_id: parentId });
}

export function updateComment(taskId: number, commentId: number, body: string): Promise<Comment> {
  return apiClient.patch<Comment>(`/tasks/${taskId}/comments/${commentId}`, { body });
}

export function deleteComment(taskId: number, commentId: number): Promise<null> {
  return apiClient.delete<null>(`/tasks/${taskId}/comments/${commentId}`);
}
