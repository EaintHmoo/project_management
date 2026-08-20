"use client";

import type { EventDropArg } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import listPlugin from "@fullcalendar/list";
import FullCalendar from "@fullcalendar/react";
import timeGridPlugin from "@fullcalendar/timegrid";
import { useRouter } from "next/navigation";
import { useState } from "react";
import { useRescheduleMeeting } from "@/features/meetings/hooks/useMeetingMutations";
import { useMeetings } from "@/features/meetings/hooks/useMeetings";
import "@/features/meetings/components/calendar.css";

export function MeetingCalendarView({ organizationId }: { organizationId: string }) {
  const router = useRouter();
  const [range, setRange] = useState<{ from: string; to: string } | null>(null);
  const { data: meetings } = useMeetings(organizationId, range?.from, range?.to);
  const rescheduleMutation = useRescheduleMeeting(organizationId);

  const events = (meetings ?? []).map((meeting) => ({
    id: String(meeting.id),
    title: meeting.title,
    start: meeting.starts_at,
    end: meeting.ends_at,
    classNames: meeting.status === "cancelled" ? ["meeting-cancelled"] : [],
    backgroundColor: meeting.status === "cancelled" ? "#a6ada5" : "#12312b",
    borderColor: meeting.status === "cancelled" ? "#a6ada5" : "#12312b",
  }));

  const onEventDrop = (info: EventDropArg) => {
    const meetingId = Number(info.event.id);
    const start = info.event.start;
    const end = info.event.end ?? start;

    if (!start) {
      info.revert();
      return;
    }

    rescheduleMutation.mutate(
      { meetingId, starts_at: start.toISOString(), ends_at: (end ?? start).toISOString() },
      { onError: () => info.revert() },
    );
  };

  return (
    <div className="meeting-calendar rounded-lg border border-[#ded7ca] bg-[#fffcf4] p-4">
      <FullCalendar
        plugins={[dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin]}
        initialView="dayGridMonth"
        headerToolbar={{
          left: "prev,next today",
          center: "title",
          right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek",
        }}
        height="auto"
        events={events}
        editable
        eventDrop={onEventDrop}
        eventClick={(info) => {
          info.jsEvent.preventDefault();
          router.push(`/meetings/${info.event.id}`);
        }}
        datesSet={(info) => {
          setRange({ from: info.start.toISOString(), to: info.end.toISOString() });
        }}
      />
    </div>
  );
}
