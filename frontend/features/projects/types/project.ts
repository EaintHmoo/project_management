export type ProjectStatus = "planning" | "active" | "on_hold" | "completed" | "archived";

export type ProjectVisibility = "organization" | "team" | "private";

export interface Project {
  id: number;
  organization_id: number;
  team_id: number | null;
  name: string;
  key: string;
  description: string | null;
  status: ProjectStatus;
  visibility: ProjectVisibility;
  starts_at: string | null;
  ends_at: string | null;
  archived_at: string | null;
  tasks_count: number | null;
  created_at: string;
  updated_at: string;
}

export interface CreateProjectInput {
  team_id?: number | null;
  name: string;
  key: string;
  description?: string | null;
  visibility?: ProjectVisibility;
  starts_at?: string | null;
  ends_at?: string | null;
}

export interface UpdateProjectInput {
  name?: string;
  description?: string | null;
  status?: ProjectStatus;
  visibility?: ProjectVisibility;
  starts_at?: string | null;
  ends_at?: string | null;
}

export const PROJECT_STATUSES: ProjectStatus[] = ["planning", "active", "on_hold", "completed", "archived"];

export const PROJECT_VISIBILITIES: ProjectVisibility[] = ["organization", "team", "private"];
