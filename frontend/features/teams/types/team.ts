export interface Team {
  id: number;
  organization_id: number;
  name: string;
  description: string | null;
  projects_count: number | null;
  created_at: string;
  updated_at: string;
}

export interface CreateTeamInput {
  name: string;
  description?: string | null;
}

export interface UpdateTeamInput {
  name?: string;
  description?: string | null;
}
