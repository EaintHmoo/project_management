"use client";

import { zodResolver } from "@hookform/resolvers/zod";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { useForm } from "react-hook-form";
import { Alert } from "@/components/ui/Alert";
import { Button } from "@/components/ui/Button";
import { FieldError } from "@/components/ui/FieldError";
import { Input } from "@/components/ui/Input";
import { Label } from "@/components/ui/Label";
import { useLogin } from "@/features/auth/hooks/useLogin";
import { loginSchema, type LoginFormValues } from "@/features/auth/schemas/loginSchema";
import { ApiError } from "@/lib/api/errors";

export function LoginForm() {
  const router = useRouter();
  const loginMutation = useLogin();
  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<LoginFormValues>({ resolver: zodResolver(loginSchema) });

  const onSubmit = handleSubmit((values) => {
    loginMutation.mutate(values, {
      onSuccess: (challenge) => {
        router.push(`/verify-2fa?challenge_id=${challenge.challenge_id}`);
      },
    });
  });

  return (
    <form onSubmit={onSubmit} className="grid gap-4" noValidate>
      {loginMutation.isError && (
        <Alert variant="error">
          {loginMutation.error instanceof ApiError
            ? loginMutation.error.message
            : "Something went wrong. Please try again."}
        </Alert>
      )}

      <div className="grid gap-1.5">
        <Label htmlFor="email">Email</Label>
        <Input id="email" type="email" autoComplete="email" {...register("email")} />
        <FieldError message={errors.email?.message} />
      </div>

      <div className="grid gap-1.5">
        <Label htmlFor="password">Password</Label>
        <Input id="password" type="password" autoComplete="current-password" {...register("password")} />
        <FieldError message={errors.password?.message} />
      </div>

      <Button type="submit" isLoading={loginMutation.isPending}>
        Sign in
      </Button>

      <p className="text-center text-sm text-[#66746e]">
        Don&apos;t have an account?{" "}
        <Link href="/register" className="font-semibold text-[#12312b]">
          Create one
        </Link>
      </p>
    </form>
  );
}
