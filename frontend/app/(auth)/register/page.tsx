import type { Metadata } from "next";
import { RegisterForm } from "@/features/auth";

export const metadata: Metadata = { title: "Create account — Nexus Collaboration" };

export default function RegisterPage() {
  return (
    <div className="grid gap-6">
      <div>
        <h2 className="text-xl font-bold text-[#18201f]">Create your account</h2>
        <p className="text-sm text-[#66746e]">Start collaborating with your team.</p>
      </div>
      <RegisterForm />
    </div>
  );
}
