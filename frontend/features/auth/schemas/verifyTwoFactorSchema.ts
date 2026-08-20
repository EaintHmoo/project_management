import { z } from "zod";

export const verifyTwoFactorSchema = z.object({
  code: z
    .string()
    .length(6, "Enter the 6-digit code")
    .regex(/^\d{6}$/, "Code must be numeric"),
});

export type VerifyTwoFactorFormValues = z.infer<typeof verifyTwoFactorSchema>;
