import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { createComment, deleteComment, listComments, updateComment } from "@/features/tasks/api/comments";

export function useComments(taskId: number | null) {
  return useQuery({
    queryKey: ["comments", taskId],
    queryFn: () => listComments(taskId as number),
    enabled: Boolean(taskId),
  });
}

export function useCreateComment(taskId: number) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: ({ body, mentions, parentId }: { body: string; mentions?: number[]; parentId?: number | null }) =>
      createComment(taskId, body, mentions, parentId),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["comments", taskId] });
      queryClient.invalidateQueries({ queryKey: ["tasks"] });
    },
  });
}

export function useUpdateComment(taskId: number) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: ({ commentId, body }: { commentId: number; body: string }) =>
      updateComment(taskId, commentId, body),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["comments", taskId] });
    },
  });
}

export function useDeleteComment(taskId: number) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (commentId: number) => deleteComment(taskId, commentId),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["comments", taskId] });
      queryClient.invalidateQueries({ queryKey: ["tasks"] });
    },
  });
}
