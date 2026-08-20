import Link from "next/link";
import { Card } from "@/components/ui/Card";
import type { Meeting } from "@/features/meetings/types/meeting";

const STATUS_LABELS: Record<Meeting["status"], string> = {
  scheduled: "Scheduled",
  cancelled: "Cancelled",
  completed: "Completed",
};

export function MeetingCard({ meeting }: { meeting: Meeting }) {
  return (
    <Link href={`/meetings/${meeting.id}`}>
      <Card className="flex items-center justify-between transition-colors hover:border-[#12312b]">
        <div>
          <h4 className="font-bold">{meeting.title}</h4>
          <p className="mt-1 text-sm text-[#66746e]">
            {new Date(meeting.starts_at).toLocaleString()} · {meeting.timezone}
          </p>
        </div>
        <span className="shrink-0 text-xs font-semibold text-[#66746e]">{STATUS_LABELS[meeting.status]}</span>
      </Card>
    </Link>
  );
}
