import { z } from "zod";

export const createTeamSchema = z.object({
  name: z.string().min(1, "Name is required").max(255),
  description: z.string().optional(),
});

export type CreateTeamFormValues = z.infer<typeof createTeamSchema>;
