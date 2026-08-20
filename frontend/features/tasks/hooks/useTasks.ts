import { useQuery } from "@tanstack/react-query";
import { getTask, listTasks } from "@/features/tasks/api/tasks";

export function useTasks(projectId: number | null) {
  return useQuery({
    queryKey: ["tasks", projectId],
    queryFn: () => listTasks(projectId as number),
    enabled: Boolean(projectId),
  });
}

export function useTask(projectId: number | null, taskId: number | null) {
  return useQuery({
    queryKey: ["tasks", projectId, taskId],
    queryFn: () => getTask(projectId as number, taskId as number),
    enabled: Boolean(projectId) && Boolean(taskId),
  });
}
