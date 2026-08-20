import type { Metadata } from "next";
import { Suspense } from "react";
import { TwoFactorForm } from "@/features/auth";

export const metadata: Metadata = { title: "Verify your identity — Nexus Collaboration" };

export default function VerifyTwoFactorPage() {
  return (
    <div className="grid gap-6">
      <div>
        <h2 className="text-xl font-bold text-[#18201f]">Verify your identity</h2>
      </div>
      <Suspense fallback={null}>
        <TwoFactorForm />
      </Suspense>
    </div>
  );
}
