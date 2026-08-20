import { z } from "zod";

export const createOrganizationSchema = z.object({
  name: z.string().min(1, "Name is required").max(255),
  slug: z
    .string()
    .max(255)
    .regex(/^[a-zA-Z0-9_-]*$/, "Slug may only contain letters, numbers, dashes, and underscores")
    .optional()
    .or(z.literal("")),
  timezone: z.string().optional(),
});

export type CreateOrganizationFormValues = z.infer<typeof createOrganizationSchema>;

export const updateOrganizationSchema = z.object({
  name: z.string().min(1, "Name is required").max(255).optional(),
  timezone: z.string().min(1).optional(),
});

export type UpdateOrganizationFormValues = z.infer<typeof updateOrganizationSchema>;
