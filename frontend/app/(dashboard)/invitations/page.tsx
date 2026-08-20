import type { Metadata } from "next";
import { InvitationsView } from "@/features/organizations";

export const metadata: Metadata = { title: "Invitations — Nexus Collaboration" };

export default function InvitationsPage() {
  return <InvitationsView />;
}
