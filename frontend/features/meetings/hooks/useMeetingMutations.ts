import { useMutation, useQueryClient } from "@tanstack/react-query";
import {
  cancelMeeting,
  createMeeting,
  deleteMeeting,
  respondToMeeting,
  updateMeeting,
} from "@/features/meetings/api/meetings";
import type { CreateMeetingInput, ParticipantResponse, UpdateMeetingInput } from "@/features/meetings/types/meeting";

export function useCreateMeeting(organizationId: string | null) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (data: CreateMeetingInput) => createMeeting(organizationId as string, data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["meetings", organizationId] });
    },
  });
}

export function useUpdateMeeting(organizationId: string | null, meetingId: number) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (data: UpdateMeetingInput) => updateMeeting(organizationId as string, meetingId, data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["meetings", organizationId] });
    },
  });
}

export function useRespondToMeeting(organizationId: string | null, meetingId: number) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (response: ParticipantResponse) => respondToMeeting(organizationId as string, meetingId, response),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["meetings", organizationId] });
    },
  });
}

export function useRescheduleMeeting(organizationId: string | null) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: ({ meetingId, starts_at, ends_at }: { meetingId: number; starts_at: string; ends_at: string }) =>
      updateMeeting(organizationId as string, meetingId, { starts_at, ends_at }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["meetings", organizationId] });
    },
  });
}

export function useCancelMeeting(organizationId: string | null, meetingId: number) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: () => cancelMeeting(organizationId as string, meetingId),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["meetings", organizationId] });
    },
  });
}

export function useDeleteMeeting(organizationId: string | null) {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (meetingId: number) => deleteMeeting(organizationId as string, meetingId),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["meetings", organizationId] });
    },
  });
}
