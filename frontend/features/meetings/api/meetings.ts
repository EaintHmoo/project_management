import { apiClient } from "@/lib/api/client";
import type {
  CreateMeetingInput,
  Meeting,
  ParticipantResponse,
  UpdateMeetingInput,
} from "@/features/meetings/types/meeting";

export function listMeetings(organizationId: string, from?: string, to?: string): Promise<Meeting[]> {
  const params = new URLSearchParams();
  if (from) params.set("from", from);
  if (to) params.set("to", to);
  const query = params.toString();

  return apiClient.get<Meeting[]>(`/organizations/${organizationId}/meetings${query ? `?${query}` : ""}`);
}

export function getMeeting(organizationId: string, meetingId: number): Promise<Meeting> {
  return apiClient.get<Meeting>(`/organizations/${organizationId}/meetings/${meetingId}`);
}

export function createMeeting(organizationId: string, data: CreateMeetingInput): Promise<Meeting> {
  return apiClient.post<Meeting>(`/organizations/${organizationId}/meetings`, data);
}

export function updateMeeting(
  organizationId: string,
  meetingId: number,
  data: UpdateMeetingInput,
): Promise<Meeting> {
  return apiClient.patch<Meeting>(`/organizations/${organizationId}/meetings/${meetingId}`, data);
}

export function respondToMeeting(
  organizationId: string,
  meetingId: number,
  response: ParticipantResponse,
): Promise<Meeting> {
  return apiClient.post<Meeting>(`/organizations/${organizationId}/meetings/${meetingId}/respond`, { response });
}

export function cancelMeeting(organizationId: string, meetingId: number): Promise<Meeting> {
  return apiClient.post<Meeting>(`/organizations/${organizationId}/meetings/${meetingId}/cancel`);
}

export function deleteMeeting(organizationId: string, meetingId: number): Promise<null> {
  return apiClient.delete<null>(`/organizations/${organizationId}/meetings/${meetingId}`);
}
