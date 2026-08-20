export type TaskStatus = "todo" | "in_progress" | "review" | "done";

export type TaskPriority = "low" | "medium" | "high" | "urgent";

export interface TaskPerson {
  id: number;
  name: string;
  email: string;
}

export interface Label {
  id: number;
  name: string;
  color: string;
}

export interface Task {
  id: number;
  project_id: number;
  title: string;
  description: string | null;
  status: TaskStatus;
  priority: TaskPriority;
  due_at: string | null;
  position: number;
  assignee: TaskPerson | null;
  reporter: TaskPerson | null;
  labels: Label[];
  comments_count: number | null;
  created_at: string;
  updated_at: string;
}

export interface TaskBoard {
  list: Task[];
  kanban: Partial<Record<TaskStatus, Task[]>>;
}

export interface Comment {
  id: number;
  task_id: number;
  parent_id: number | null;
  body: string;
  mentions: number[] | null;
  author: TaskPerson;
  replies: Comment[];
  created_at: string;
  updated_at: string;
}

export interface CreateTaskInput {
  assignee_id?: number | null;
  title: string;
  description?: string | null;
  priority?: TaskPriority;
  due_at?: string | null;
  label_ids?: number[];
}

export interface UpdateTaskInput {
  title?: string;
  description?: string | null;
  status?: TaskStatus;
  priority?: TaskPriority;
  due_at?: string | null;
  label_ids?: number[];
}

export const TASK_STATUSES: { key: TaskStatus; label: string }[] = [
  { key: "todo", label: "Todo" },
  { key: "in_progress", label: "In Progress" },
  { key: "review", label: "Review" },
  { key: "done", label: "Done" },
];

export const TASK_PRIORITIES: TaskPriority[] = ["low", "medium", "high", "urgent"];
