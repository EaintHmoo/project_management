import { useQuery } from "@tanstack/react-query";
import { getMeeting, listMeetings } from "@/features/meetings/api/meetings";

export function useMeetings(organizationId: string | null, from?: string, to?: string) {
  return useQuery({
    queryKey: ["meetings", organizationId, from, to],
    queryFn: () => listMeetings(organizationId as string, from, to),
    enabled: Boolean(organizationId),
  });
}

export function useMeeting(organizationId: string | null, meetingId: number | null) {
  return useQuery({
    queryKey: ["meetings", organizationId, meetingId],
    queryFn: () => getMeeting(organizationId as string, meetingId as number),
    enabled: Boolean(organizationId) && Boolean(meetingId),
  });
}
