import { useMutation, useQueryClient } from "@tanstack/react-query";
import { archiveProject, createProject, deleteProject, updateProject } from "@/features/projects/api/projects";
import type { CreateProjectInput, UpdateProjectInput } from "@/features/projects/types/project";

export function useCreateProject(organizationId: string | null) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (data: CreateProjectInput) => createProject(organizationId as string, data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["projects", organizationId] });
    },
  });
}

export function useUpdateProject(organizationId: string | null, projectId: number) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (data: UpdateProjectInput) => updateProject(organizationId as string, projectId, data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["projects", organizationId] });
    },
  });
}

export function useArchiveProject(organizationId: string | null, projectId: number) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: () => archiveProject(organizationId as string, projectId),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["projects", organizationId] });
    },
  });
}

export function useDeleteProject(organizationId: string | null) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (projectId: number) => deleteProject(organizationId as string, projectId),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["projects", organizationId] });
    },
  });
}
