"use client";

import { zodResolver } from "@hookform/resolvers/zod";
import Link from "next/link";
import { useForm } from "react-hook-form";
import { Alert } from "@/components/ui/Alert";
import { Button, buttonVariants } from "@/components/ui/Button";
import { FieldError } from "@/components/ui/FieldError";
import { Input } from "@/components/ui/Input";
import { Label } from "@/components/ui/Label";
import { useRegister } from "@/features/auth/hooks/useRegister";
import { registerSchema, type RegisterFormValues } from "@/features/auth/schemas/registerSchema";
import { ApiError } from "@/lib/api/errors";

export function RegisterForm() {
  const registerMutation = useRegister();
  const {
    register: registerField,
    handleSubmit,
    formState: { errors },
  } = useForm<RegisterFormValues>({ resolver: zodResolver(registerSchema) });

  const onSubmit = handleSubmit((values) => {
    registerMutation.mutate(values);
  });

  if (registerMutation.isSuccess) {
    return (
      <div className="grid gap-4">
        <Alert variant="success">
          Registration successful. Check your email to verify your account, then sign in.
        </Alert>
        <Link href="/login" className={buttonVariants({ variant: "secondary" })}>
          Go to sign in
        </Link>
      </div>
    );
  }

  return (
    <form onSubmit={onSubmit} className="grid gap-4" noValidate>
      {registerMutation.isError && (
        <Alert variant="error">
          {registerMutation.error instanceof ApiError
            ? registerMutation.error.message
            : "Something went wrong. Please try again."}
        </Alert>
      )}

      <div className="grid gap-1.5">
        <Label htmlFor="name">Full name</Label>
        <Input id="name" autoComplete="name" {...registerField("name")} />
        <FieldError message={errors.name?.message} />
      </div>

      <div className="grid gap-1.5">
        <Label htmlFor="email">Email</Label>
        <Input id="email" type="email" autoComplete="email" {...registerField("email")} />
        <FieldError message={errors.email?.message} />
      </div>

      <div className="grid gap-1.5">
        <Label htmlFor="password">Password</Label>
        <Input id="password" type="password" autoComplete="new-password" {...registerField("password")} />
        <FieldError message={errors.password?.message} />
      </div>

      <div className="grid gap-1.5">
        <Label htmlFor="password_confirmation">Confirm password</Label>
        <Input
          id="password_confirmation"
          type="password"
          autoComplete="new-password"
          {...registerField("password_confirmation")}
        />
        <FieldError message={errors.password_confirmation?.message} />
      </div>

      <Button type="submit" isLoading={registerMutation.isPending}>
        Create account
      </Button>

      <p className="text-center text-sm text-[#66746e]">
        Already have an account?{" "}
        <Link href="/login" className="font-semibold text-[#12312b]">
          Sign in
        </Link>
      </p>
    </form>
  );
}
