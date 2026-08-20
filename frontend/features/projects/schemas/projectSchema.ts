import { z } from "zod";

export const createProjectSchema = z.object({
  name: z.string().min(1, "Name is required").max(255),
  key: z
    .string()
    .min(1, "Key is required")
    .max(12, "Key must be 12 characters or fewer")
    .regex(/^[a-zA-Z0-9_-]+$/, "Key may only contain letters, numbers, dashes, and underscores"),
  description: z.string().optional(),
  visibility: z.enum(["organization", "team", "private"]).optional(),
  team_id: z.string().optional(),
  starts_at: z.string().optional(),
  ends_at: z.string().optional(),
});

export type CreateProjectFormValues = z.infer<typeof createProjectSchema>;
