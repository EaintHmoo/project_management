import type { Metadata } from "next";
import { MeetingDetailView } from "@/features/meetings";

export const metadata: Metadata = { title: "Meeting — Nexus Collaboration" };

export default async function MeetingDetailPage({
  params,
}: {
  params: Promise<{ meetingId: string }>;
}) {
  const { meetingId } = await params;

  return <MeetingDetailView meetingId={Number(meetingId)} />;
}
