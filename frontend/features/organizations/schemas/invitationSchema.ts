import { z } from "zod";

export const inviteMemberSchema = z.object({
  email: z.string().email("Enter a valid email address"),
  role: z.enum(["owner", "admin", "manager", "member", "guest"]),
});

export type InviteMemberFormValues = z.infer<typeof inviteMemberSchema>;
