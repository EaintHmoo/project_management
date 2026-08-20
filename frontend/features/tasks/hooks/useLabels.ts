import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { createLabel, deleteLabel, listLabels } from "@/features/tasks/api/labels";

export function useLabels(organizationId: string | null) {
  return useQuery({
    queryKey: ["labels", organizationId],
    queryFn: () => listLabels(organizationId as string),
    enabled: Boolean(organizationId),
  });
}

export function useCreateLabel(organizationId: string | null) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: ({ name, color }: { name: string; color: string }) =>
      createLabel(organizationId as string, name, color),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["labels", organizationId] });
    },
  });
}

export function useDeleteLabel(organizationId: string | null) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (labelId: number) => deleteLabel(organizationId as string, labelId),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["labels", organizationId] });
    },
  });
}
