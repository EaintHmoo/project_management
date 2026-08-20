import type { Metadata } from "next";
import { OrganizationSettingsView } from "@/features/organizations";

export const metadata: Metadata = { title: "Organization settings — Nexus Collaboration" };

export default function OrganizationSettingsPage() {
  return <OrganizationSettingsView />;
}
