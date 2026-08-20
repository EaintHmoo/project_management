"use client";

import { zodResolver } from "@hookform/resolvers/zod";
import { useRouter, useSearchParams } from "next/navigation";
import { useEffect, useState } from "react";
import { useForm } from "react-hook-form";
import { Alert } from "@/components/ui/Alert";
import { Button } from "@/components/ui/Button";
import { FieldError } from "@/components/ui/FieldError";
import { Input } from "@/components/ui/Input";
import { Label } from "@/components/ui/Label";
import { useResendTwoFactor } from "@/features/auth/hooks/useResendTwoFactor";
import { useVerifyTwoFactor } from "@/features/auth/hooks/useVerifyTwoFactor";
import {
  verifyTwoFactorSchema,
  type VerifyTwoFactorFormValues,
} from "@/features/auth/schemas/verifyTwoFactorSchema";
import { ApiError } from "@/lib/api/errors";

const RESEND_COOLDOWN_SECONDS = 60;

export function TwoFactorForm() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const challengeIdParam = searchParams.get("challenge_id");
  const [challengeId, setChallengeId] = useState<number | null>(
    challengeIdParam ? Number(challengeIdParam) : null,
  );
  const [cooldown, setCooldown] = useState(0);

  const verifyMutation = useVerifyTwoFactor();
  const resendMutation = useResendTwoFactor();

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<VerifyTwoFactorFormValues>({ resolver: zodResolver(verifyTwoFactorSchema) });

  useEffect(() => {
    if (cooldown <= 0) return;
    const timer = setInterval(() => setCooldown((value) => Math.max(0, value - 1)), 1000);
    return () => clearInterval(timer);
  }, [cooldown]);

  if (!challengeId) {
    return (
      <Alert variant="error">
        Missing verification session. Please{" "}
        <a href="/login" className="font-semibold underline">
          sign in
        </a>{" "}
        again.
      </Alert>
    );
  }

  const onSubmit = handleSubmit((values) => {
    verifyMutation.mutate(
      { challenge_id: challengeId, code: values.code },
      { onSuccess: () => router.push("/dashboard") },
    );
  });

  const onResend = () => {
    resendMutation.mutate(
      { challenge_id: challengeId },
      {
        onSuccess: (challenge) => {
          setChallengeId(challenge.challenge_id);
          setCooldown(RESEND_COOLDOWN_SECONDS);
        },
      },
    );
  };

  return (
    <form onSubmit={onSubmit} className="grid gap-4" noValidate>
      <p className="text-sm text-[#66746e]">
        Enter the 6-digit code we emailed you. It expires in a few minutes.
      </p>

      {verifyMutation.isError && (
        <Alert variant="error">
          {verifyMutation.error instanceof ApiError
            ? verifyMutation.error.message
            : "Something went wrong. Please try again."}
        </Alert>
      )}
      {resendMutation.isSuccess && <Alert variant="success">A new code has been sent.</Alert>}
      {resendMutation.isError && (
        <Alert variant="error">
          {resendMutation.error instanceof ApiError
            ? resendMutation.error.message
            : "Could not resend the code."}
        </Alert>
      )}

      <div className="grid gap-1.5">
        <Label htmlFor="code">Verification code</Label>
        <Input
          id="code"
          inputMode="numeric"
          autoComplete="one-time-code"
          maxLength={6}
          {...register("code")}
        />
        <FieldError message={errors.code?.message} />
      </div>

      <Button type="submit" isLoading={verifyMutation.isPending}>
        Verify and sign in
      </Button>

      <Button
        type="button"
        variant="ghost"
        disabled={cooldown > 0}
        isLoading={resendMutation.isPending}
        onClick={onResend}
      >
        {cooldown > 0 ? `Resend code in ${cooldown}s` : "Resend code"}
      </Button>
    </form>
  );
}
