import { z } from "zod";

export const createMeetingSchema = z
  .object({
    title: z.string().min(1, "Title is required").max(255),
    description: z.string().optional(),
    starts_at: z.string().min(1, "Start time is required"),
    ends_at: z.string().min(1, "End time is required"),
    timezone: z.string().min(1, "Timezone is required"),
  })
  .refine((data) => new Date(data.ends_at) > new Date(data.starts_at), {
    message: "End time must be after start time",
    path: ["ends_at"],
  });

export type CreateMeetingFormValues = z.infer<typeof createMeetingSchema>;
