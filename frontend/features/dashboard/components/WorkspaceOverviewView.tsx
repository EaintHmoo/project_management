"use client";

import { Card } from "@/components/ui/Card";
import { useWorkspaceOverview } from "@/features/dashboard/hooks/useWorkspaceOverview";

const KANBAN_COLUMNS = [
  { key: "todo", label: "Todo" },
  { key: "in_progress", label: "In Progress" },
  { key: "review", label: "Review" },
  { key: "done", label: "Done" },
];

export function WorkspaceOverviewView() {
  const { data, isLoading, isError } = useWorkspaceOverview();

  if (isLoading) {
    return <p className="text-sm text-[#66746e]">Loading workspace…</p>;
  }

  if (isError) {
    return (
      <Card className="border-[#f4c9c0] bg-[#f4e7e3] text-[#8f321f]">
        Could not load your workspace. Is the API running?
      </Card>
    );
  }

  if (!data) {
    return (
      <Card>
        <h3 className="text-lg font-bold">No workspace yet</h3>
        <p className="mt-2 text-sm text-[#66746e]">
          Create an organization to get started with projects, tasks, and meetings.
        </p>
      </Card>
    );
  }

  const metrics = [
    { label: "Active projects", value: data.metrics.active_projects },
    { label: "Open tasks", value: data.metrics.open_tasks },
    { label: "Completed", value: data.metrics.completed_tasks },
    { label: "Members", value: data.metrics.members },
  ];

  return (
    <div className="grid gap-6">
      <section className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        {metrics.map((metric) => (
          <Card key={metric.label}>
            <p className="text-sm font-medium text-[#66746e]">{metric.label}</p>
            <strong className="mt-3 block text-3xl">{metric.value}</strong>
          </Card>
        ))}
      </section>

      <section className="grid gap-6 xl:grid-cols-[1fr_360px]">
        <div className="min-w-0">
          <h3 className="mb-3 text-xl font-bold">Task board</h3>
          <div className="grid gap-4 xl:grid-cols-4">
            {KANBAN_COLUMNS.map((column) => {
              const tasks = data.kanban[column.key] ?? [];

              return (
                <div key={column.key} className="rounded-lg border border-[#ddd4c3] bg-[#fffcf4] p-3">
                  <div className="mb-3 flex items-center justify-between">
                    <h4 className="font-semibold">{column.label}</h4>
                    <span className="rounded-md bg-[#ece3d2] px-2 py-1 text-xs font-semibold">
                      {tasks.length}
                    </span>
                  </div>
                  <div className="grid gap-3">
                    {tasks.map((task) => (
                      <article key={task.id} className="rounded-lg border border-[#ded7ca] bg-white p-3">
                        <h5 className="text-sm font-bold leading-5">{task.title}</h5>
                        {task.labels.length > 0 && (
                          <div className="mt-3 flex flex-wrap gap-1">
                            {task.labels.map((label) => (
                              <span
                                key={label.id}
                                className="rounded-md bg-[#edf2ef] px-2 py-1 text-xs text-[#3d5d51]"
                              >
                                {label.name}
                              </span>
                            ))}
                          </div>
                        )}
                        {task.assignee && (
                          <p className="mt-3 text-xs font-medium text-[#66746e]">
                            Assigned to {task.assignee.name}
                          </p>
                        )}
                      </article>
                    ))}
                    {tasks.length === 0 && <p className="text-xs text-[#9aa39c]">No tasks</p>}
                  </div>
                </div>
              );
            })}
          </div>
        </div>

        <aside>
          <h3 className="mb-3 text-xl font-bold">Upcoming meetings</h3>
          <div className="grid gap-3">
            {data.meetings.map((meeting) => (
              <Card key={meeting.id}>
                <h4 className="font-bold">{meeting.title}</h4>
                <p className="mt-1 text-sm text-[#66746e]">
                  {new Date(meeting.starts_at).toLocaleString()}
                </p>
              </Card>
            ))}
            {data.meetings.length === 0 && <p className="text-sm text-[#9aa39c]">No upcoming meetings</p>}
          </div>
        </aside>
      </section>

      <section>
        <h3 className="mb-3 text-xl font-bold">Projects</h3>
        <div className="grid gap-3">
          {data.projects.map((project) => (
            <Card key={project.id} className="flex items-center justify-between">
              <div>
                <h4 className="font-bold">{project.name}</h4>
                <p className="text-sm text-[#66746e]">
                  {project.status} · {project.tasks_count} tasks
                </p>
              </div>
            </Card>
          ))}
        </div>
      </section>
    </div>
  );
}
