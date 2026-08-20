"use client";

import { useState } from "react";
import { Button } from "@/components/ui/Button";
import { Card } from "@/components/ui/Card";
import { CreateMeetingForm } from "@/features/meetings/components/CreateMeetingForm";
import { MeetingCalendarView } from "@/features/meetings/components/MeetingCalendarView";
import { MeetingCard } from "@/features/meetings/components/MeetingCard";
import { useMeetings } from "@/features/meetings/hooks/useMeetings";
import { useCurrentOrganization } from "@/features/organizations";

export function MeetingListView() {
  const { organizationId, isLoading: isOrganizationLoading } = useCurrentOrganization();
  const { data: meetings, isLoading, isError } = useMeetings(organizationId);
  const [isCreating, setIsCreating] = useState(false);
  const [view, setView] = useState<"list" | "calendar">("list");

  if (isOrganizationLoading || isLoading) {
    return <p className="text-sm text-[#66746e]">Loading meetings…</p>;
  }

  if (!organizationId) {
    return (
      <Card>
        <h3 className="text-lg font-bold">No workspace yet</h3>
        <p className="mt-2 text-sm text-[#66746e]">Create an organization to start scheduling meetings.</p>
      </Card>
    );
  }

  if (isError) {
    return (
      <Card className="border-[#f4c9c0] bg-[#f4e7e3] text-[#8f321f]">
        Could not load meetings. Is the API running?
      </Card>
    );
  }

  return (
    <div className="grid gap-6">
      <div className="flex flex-wrap items-center justify-between gap-3">
        <h2 className="text-xl font-bold">Meetings</h2>
        <div className="flex items-center gap-2">
          <div className="flex rounded-md border border-[#d8cfbd] bg-white p-0.5">
            <button
              type="button"
              onClick={() => setView("list")}
              className={`rounded px-3 py-1.5 text-sm font-semibold ${
                view === "list" ? "bg-[#12312b] text-white" : "text-[#4f5f58] hover:bg-[#efe8da]"
              }`}
            >
              List
            </button>
            <button
              type="button"
              onClick={() => setView("calendar")}
              className={`rounded px-3 py-1.5 text-sm font-semibold ${
                view === "calendar" ? "bg-[#12312b] text-white" : "text-[#4f5f58] hover:bg-[#efe8da]"
              }`}
            >
              Calendar
            </button>
          </div>
          <Button size="sm" variant={isCreating ? "secondary" : "primary"} onClick={() => setIsCreating((v) => !v)}>
            {isCreating ? "Cancel" : "New meeting"}
          </Button>
        </div>
      </div>

      {isCreating && (
        <Card>
          <CreateMeetingForm organizationId={organizationId} onCreated={() => setIsCreating(false)} />
        </Card>
      )}

      {view === "calendar" ? (
        <MeetingCalendarView organizationId={organizationId} />
      ) : meetings && meetings.length > 0 ? (
        <div className="grid gap-3">
          {meetings.map((meeting) => (
            <MeetingCard key={meeting.id} meeting={meeting} />
          ))}
        </div>
      ) : (
        <Card>
          <p className="text-sm text-[#66746e]">No meetings scheduled yet.</p>
        </Card>
      )}
    </div>
  );
}
