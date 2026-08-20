import type { Metadata } from "next";
import { LoginForm } from "@/features/auth";

export const metadata: Metadata = { title: "Sign in — Nexus Collaboration" };

export default function LoginPage() {
  return (
    <div className="grid gap-6">
      <div>
        <h2 className="text-xl font-bold text-[#18201f]">Sign in</h2>
        <p className="text-sm text-[#66746e]">Welcome back. Enter your details to continue.</p>
      </div>
      <LoginForm />
    </div>
  );
}
