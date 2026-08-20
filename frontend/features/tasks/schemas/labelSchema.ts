import { z } from "zod";

export const createLabelSchema = z.object({
  name: z.string().min(1, "Name is required").max(255),
  color: z
    .string()
    .regex(/^#[0-9a-fA-F]{6}$/, "Use a hex color like #12312b"),
});

export type CreateLabelFormValues = z.infer<typeof createLabelSchema>;
