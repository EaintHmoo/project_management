import { z } from "zod";

export const createTaskSchema = z.object({
  title: z.string().min(1, "Title is required").max(255),
  description: z.string().optional(),
  priority: z.enum(["low", "medium", "high", "urgent"]).optional(),
  due_at: z.string().optional(),
  assignee_id: z.string().optional(),
});

export type CreateTaskFormValues = z.infer<typeof createTaskSchema>;
