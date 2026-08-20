"use client";

import { useRouter } from "next/navigation";
import { Alert } from "@/components/ui/Alert";
import { Button } from "@/components/ui/Button";
import { Card } from "@/components/ui/Card";
import { Input } from "@/components/ui/Input";
import { Label } from "@/components/ui/Label";
import {
  useCancelMeeting,
  useDeleteMeeting,
  useRespondToMeeting,
  useUpdateMeeting,
} from "@/features/meetings/hooks/useMeetingMutations";
import { useMeeting } from "@/features/meetings/hooks/useMeetings";
import { useCurrentOrganization } from "@/features/organizations";
import { useAuthStore } from "@/stores/authStore";

const RESPONSE_LABELS: Record<string, string> = {
  pending: "Pending",
  accepted: "Accepted",
  declined: "Declined",
};

export function MeetingDetailView({ meetingId }: { meetingId: number }) {
  const router = useRouter();
  const currentUser = useAuthStore((state) => state.user);
  const { organizationId, isLoading: isOrganizationLoading } = useCurrentOrganization();
  const { data: meeting, isLoading, isError } = useMeeting(organizationId, meetingId);
  const updateMeetingMutation = useUpdateMeeting(organizationId, meetingId);
  const respondMutation = useRespondToMeeting(organizationId, meetingId);
  const cancelMutation = useCancelMeeting(organizationId, meetingId);
  const deleteMutation = useDeleteMeeting(organizationId);

  if (isOrganizationLoading || isLoading) {
    return <p className="text-sm text-[#66746e]">Loading meeting…</p>;
  }

  if (isError || !meeting) {
    return <Alert variant="error">Could not load this meeting.</Alert>;
  }

  const isHost = meeting.host_id === currentUser?.id;
  const myParticipant = meeting.participants?.find((p) => p.id === currentUser?.id);

  const onDelete = () => {
    if (!window.confirm(`Delete "${meeting.title}"? This cannot be undone.`)) {
      return;
    }

    deleteMutation.mutate(meeting.id, { onSuccess: () => router.push("/meetings") });
  };

  return (
    <div className="grid gap-6">
      <div className="flex flex-wrap items-start justify-between gap-4">
        <div>
          <Input
            defaultValue={meeting.title}
            className="text-lg font-bold"
            disabled={!isHost || meeting.status !== "scheduled"}
            onBlur={(e) =>
              isHost && e.target.value !== meeting.title && updateMeetingMutation.mutate({ title: e.target.value })
            }
          />
          <p className="mt-2 text-sm text-[#66746e]">
            {new Date(meeting.starts_at).toLocaleString()} – {new Date(meeting.ends_at).toLocaleString()} (
            {meeting.timezone})
          </p>
          <span className="mt-1 inline-block text-xs font-semibold uppercase text-[#9aa39c]">{meeting.status}</span>
        </div>

        {isHost && meeting.status === "scheduled" && (
          <div className="flex items-center gap-2">
            <Button variant="secondary" size="sm" isLoading={cancelMutation.isPending} onClick={() => cancelMutation.mutate()}>
              Cancel meeting
            </Button>
            <Button variant="danger" size="sm" isLoading={deleteMutation.isPending} onClick={onDelete}>
              Delete
            </Button>
          </div>
        )}
      </div>

      <Card>
        <Label htmlFor="description">Description</Label>
        <textarea
          id="description"
          defaultValue={meeting.description ?? ""}
          disabled={!isHost || meeting.status !== "scheduled"}
          placeholder="No description"
          className="mt-1.5 min-h-[80px] w-full rounded-md border border-[#d8cfbd] bg-white p-3 text-sm outline-none focus:border-[#12312b] disabled:bg-[#f7f5ef]"
          onBlur={(e) =>
            isHost &&
            e.target.value !== (meeting.description ?? "") &&
            updateMeetingMutation.mutate({ description: e.target.value })
          }
        />
      </Card>

      {myParticipant && meeting.status === "scheduled" && (
        <Card className="flex items-center justify-between">
          <p className="text-sm font-medium">
            Your response: <strong>{RESPONSE_LABELS[myParticipant.response_status]}</strong>
          </p>
          <div className="flex items-center gap-2">
            <Button
              size="sm"
              variant={myParticipant.response_status === "accepted" ? "primary" : "secondary"}
              isLoading={respondMutation.isPending}
              onClick={() => respondMutation.mutate("accepted")}
            >
              Accept
            </Button>
            <Button
              size="sm"
              variant={myParticipant.response_status === "declined" ? "danger" : "secondary"}
              isLoading={respondMutation.isPending}
              onClick={() => respondMutation.mutate("declined")}
            >
              Decline
            </Button>
          </div>
        </Card>
      )}

      <div>
        <h3 className="mb-3 text-lg font-bold">Participants</h3>
        <div className="grid gap-2">
          {(meeting.participants ?? []).map((participant) => (
            <Card key={participant.id} className="flex items-center justify-between">
              <div>
                <p className="font-medium">{participant.name}</p>
                <p className="text-xs text-[#66746e]">{participant.email}</p>
              </div>
              <span className="text-xs font-semibold text-[#66746e]">
                {RESPONSE_LABELS[participant.response_status]}
              </span>
            </Card>
          ))}
          {(!meeting.participants || meeting.participants.length === 0) && (
            <p className="text-sm text-[#9aa39c]">No participants invited.</p>
          )}
        </div>
      </div>
    </div>
  );
}
