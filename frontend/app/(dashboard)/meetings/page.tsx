import type { Metadata } from "next";
import { MeetingListView } from "@/features/meetings";

export const metadata: Metadata = { title: "Meetings — Nexus Collaboration" };

export default function MeetingsPage() {
  return <MeetingListView />;
}
