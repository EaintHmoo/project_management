export type MeetingStatus = "scheduled" | "cancelled" | "completed";

export type ParticipantResponse = "pending" | "accepted" | "declined";

export interface MeetingParticipant {
  id: number;
  name: string;
  email: string;
  response_status: ParticipantResponse;
}

export interface Meeting {
  id: number;
  organization_id: number;
  host_id: number;
  title: string;
  description: string | null;
  starts_at: string;
  ends_at: string;
  timezone: string;
  status: MeetingStatus;
  recurrence_rule: string | null;
  video_room_provider: string | null;
  video_room_id: string | null;
  participants: MeetingParticipant[] | null;
  created_at: string;
  updated_at: string;
}

export interface CreateMeetingInput {
  title: string;
  description?: string | null;
  starts_at: string;
  ends_at: string;
  timezone: string;
  recurrence_rule?: string | null;
  participant_ids?: number[];
}

export interface UpdateMeetingInput {
  title?: string;
  description?: string | null;
  starts_at?: string;
  ends_at?: string;
  timezone?: string;
  recurrence_rule?: string | null;
}
