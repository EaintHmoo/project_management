export interface OrganizationSummary {
  id: number;
  name: string;
  slug: string;
  timezone: string;
  members_count: number;
  projects_count: number;
}

export interface ProjectSummary {
  id: number;
  name: string;
  key: string;
  status: string;
  tasks_count: number;
}

export interface TaskAssignee {
  id: number;
  name: string;
  email: string;
}

export interface TaskLabel {
  id: number;
  name: string;
  color: string;
}

export interface TaskSummary {
  id: number;
  title: string;
  status: string;
  priority: string;
  position: number;
  assignee: TaskAssignee | null;
  labels: TaskLabel[];
}

export interface MeetingSummary {
  id: number;
  title: string;
  starts_at: string;
  ends_at: string;
  participants: Array<{ id: number; name: string; email: string }>;
}

export interface WorkspaceOverview {
  organization: OrganizationSummary;
  metrics: {
    active_projects: number;
    open_tasks: number;
    completed_tasks: number;
    upcoming_meetings: number;
    members: number;
  };
  tasks_by_status: Record<string, number>;
  projects: ProjectSummary[];
  kanban: Record<string, TaskSummary[]>;
  meetings: MeetingSummary[];
}
