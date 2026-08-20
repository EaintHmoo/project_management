const metrics = [
  { label: "Active projects", value: "12", delta: "+3 this month" },
  { label: "Open tasks", value: "184", delta: "23 high priority" },
  { label: "Completed", value: "1,024", delta: "78% completion" },
  { label: "Members", value: "48", delta: "5 teams" },
];

const columns = [
  {
    title: "Todo",
    count: 3,
    tasks: [
      {
        title: "Create tenant-aware organization switcher",
        project: "CORE",
        assignee: "Alice",
        priority: "High",
        labels: ["Frontend", "Backend"],
      },
      {
        title: "Define invitation accept and reject flow",
        project: "TEAM",
        assignee: "Sarah",
        priority: "Medium",
        labels: ["RBAC"],
      },
    ],
  },
  {
    title: "In Progress",
    count: 4,
    tasks: [
      {
        title: "Add policy map for project and task actions",
        project: "CORE",
        assignee: "Bob",
        priority: "High",
        labels: ["Security", "Backend"],
      },
      {
        title: "Build advanced filters with URL query sync",
        project: "TASK",
        assignee: "Maya",
        priority: "Medium",
        labels: ["Filters"],
      },
    ],
  },
  {
    title: "Review",
    count: 2,
    tasks: [
      {
        title: "Design task comments with mentions and replies",
        project: "TASK",
        assignee: "Sarah",
        priority: "Medium",
        labels: ["Design"],
      },
    ],
  },
  {
    title: "Done",
    count: 8,
    tasks: [
      {
        title: "Store meetings in UTC with timezone rendering",
        project: "MEET",
        assignee: "Alice",
        priority: "Medium",
        labels: ["Calendar"],
      },
    ],
  },
];

const projects = [
  { name: "Core Collaboration Platform", status: "Active", tasks: 74, progress: 68 },
  { name: "Video-ready Scheduling", status: "Planning", tasks: 31, progress: 36 },
  { name: "Notifications and Audit Trail", status: "Active", tasks: 42, progress: 54 },
];

const meetings = [
  { title: "Engineering Daily Standup", time: "09:00 AM MMT", recurrence: "Weekdays", people: 9 },
  { title: "Sprint Planning", time: "Monday 10:00 AM", recurrence: "Every 2 weeks", people: 14 },
  { title: "Design Review", time: "Thursday 02:30 PM", recurrence: "Weekly", people: 6 },
];

const activity = [
  "Alice moved RBAC policy map to In Progress",
  "Bob mentioned Alice in API tests discussion",
  "Sarah scheduled Sprint Planning for next Monday",
  "Admin invited Maya to Engineering",
];

export default function Home() {
  return (
    <main className="min-h-screen bg-[#f7f5ef] text-[#18201f]">
      <div className="flex min-h-screen">
        <aside className="hidden w-64 shrink-0 border-r border-[#ded7ca] bg-[#fffcf4] px-5 py-6 lg:block">
          <div className="mb-8">
            <p className="text-xs font-semibold uppercase tracking-[0.18em] text-[#66746e]">
              Nexus
            </p>
            <h1 className="mt-2 text-2xl font-bold">Collaboration</h1>
          </div>

          <div className="rounded-lg border border-[#ddd4c3] bg-white p-3">
            <p className="text-xs font-medium text-[#66746e]">Organization</p>
            <div className="mt-2 flex items-center justify-between gap-3">
              <span className="font-semibold">Nexus Collaboration</span>
              <span className="rounded-md bg-[#e9f5ee] px-2 py-1 text-xs text-[#1d6b42]">
                Owner
              </span>
            </div>
          </div>

          <nav className="mt-8 grid gap-1 text-sm font-medium text-[#4f5f58]">
            {["Dashboard", "Projects", "Tasks", "Calendar", "Files", "Analytics", "Settings"].map(
              (item) => (
                <a
                  className={`rounded-md px-3 py-2 ${
                    item === "Dashboard"
                      ? "bg-[#12312b] text-white"
                      : "hover:bg-[#efe8da]"
                  }`}
                  href="#"
                  key={item}
                >
                  {item}
                </a>
              ),
            )}
          </nav>
        </aside>

        <section className="flex min-w-0 flex-1 flex-col">
          <header className="border-b border-[#ded7ca] bg-[#fffcf4]/90 px-4 py-4 backdrop-blur md:px-8">
            <div className="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
              <div>
                <p className="text-sm font-semibold text-[#66746e]">Multi-tenant SaaS workspace</p>
                <h2 className="mt-1 text-3xl font-bold tracking-normal">Project command center</h2>
              </div>
              <div className="flex flex-col gap-3 sm:flex-row">
                <label className="flex h-11 min-w-0 items-center rounded-md border border-[#d8cfbd] bg-white px-3">
                  <span className="mr-2 text-[#66746e]">Search</span>
                  <input
                    className="min-w-0 flex-1 bg-transparent outline-none"
                    placeholder="Projects, tasks, meetings"
                  />
                </label>
                <button className="h-11 rounded-md bg-[#c94f38] px-4 font-semibold text-white shadow-sm">
                  New task
                </button>
              </div>
            </div>
          </header>

          <div className="grid gap-6 px-4 py-6 md:px-8">
            <section className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
              {metrics.map((metric) => (
                <div className="rounded-lg border border-[#ddd4c3] bg-white p-4" key={metric.label}>
                  <p className="text-sm font-medium text-[#66746e]">{metric.label}</p>
                  <div className="mt-3 flex items-end justify-between gap-4">
                    <strong className="text-3xl">{metric.value}</strong>
                    <span className="text-sm text-[#2e7a50]">{metric.delta}</span>
                  </div>
                </div>
              ))}
            </section>

            <section className="grid gap-6 xl:grid-cols-[1fr_360px]">
              <div className="min-w-0">
                <div className="mb-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                  <div>
                    <h3 className="text-xl font-bold">Task board</h3>
                    <p className="text-sm text-[#66746e]">
                      Kanban, list, and calendar data share one task model.
                    </p>
                  </div>
                  <div className="flex flex-wrap gap-2">
                    {["Status", "Priority", "Assignee", "Due this week"].map((filter) => (
                      <button
                        className="rounded-md border border-[#d8cfbd] bg-white px-3 py-2 text-sm font-medium"
                        key={filter}
                      >
                        {filter}
                      </button>
                    ))}
                  </div>
                </div>

                <div className="grid gap-4 xl:grid-cols-4">
                  {columns.map((column) => (
                    <div className="rounded-lg border border-[#ddd4c3] bg-[#fffcf4] p-3" key={column.title}>
                      <div className="mb-3 flex items-center justify-between">
                        <h4 className="font-semibold">{column.title}</h4>
                        <span className="rounded-md bg-[#ece3d2] px-2 py-1 text-xs font-semibold">
                          {column.count}
                        </span>
                      </div>
                      <div className="grid gap-3">
                        {column.tasks.map((task) => (
                          <article className="rounded-lg border border-[#ded7ca] bg-white p-3" key={task.title}>
                            <div className="flex items-center justify-between gap-2 text-xs">
                              <span className="font-bold text-[#c94f38]">{task.project}</span>
                              <span className="rounded-md bg-[#f4e7e3] px-2 py-1 text-[#8f321f]">
                                {task.priority}
                              </span>
                            </div>
                            <h5 className="mt-3 text-sm font-bold leading-5">{task.title}</h5>
                            <div className="mt-3 flex flex-wrap gap-1">
                              {task.labels.map((label) => (
                                <span className="rounded-md bg-[#edf2ef] px-2 py-1 text-xs text-[#3d5d51]" key={label}>
                                  {label}
                                </span>
                              ))}
                            </div>
                            <p className="mt-3 text-xs font-medium text-[#66746e]">Assigned to {task.assignee}</p>
                          </article>
                        ))}
                      </div>
                    </div>
                  ))}
                </div>
              </div>

              <aside className="grid gap-6">
                <section>
                  <h3 className="mb-3 text-xl font-bold">Upcoming meetings</h3>
                  <div className="grid gap-3">
                    {meetings.map((meeting) => (
                      <article className="rounded-lg border border-[#ddd4c3] bg-white p-4" key={meeting.title}>
                        <div className="flex items-start justify-between gap-3">
                          <div>
                            <h4 className="font-bold">{meeting.title}</h4>
                            <p className="mt-1 text-sm text-[#66746e]">{meeting.time}</p>
                          </div>
                          <span className="rounded-md bg-[#e7f0f3] px-2 py-1 text-xs font-semibold text-[#24566a]">
                            {meeting.people}
                          </span>
                        </div>
                        <p className="mt-3 text-sm text-[#4f5f58]">{meeting.recurrence}</p>
                      </article>
                    ))}
                  </div>
                </section>

                <section className="rounded-lg border border-[#ddd4c3] bg-white p-4">
                  <h3 className="text-xl font-bold">Notifications</h3>
                  <div className="mt-4 grid gap-3">
                    {activity.map((item) => (
                      <p className="rounded-md bg-[#f7f5ef] px-3 py-2 text-sm text-[#4f5f58]" key={item}>
                        {item}
                      </p>
                    ))}
                  </div>
                </section>
              </aside>
            </section>

            <section className="grid gap-6 xl:grid-cols-[1fr_420px]">
              <div>
                <div className="mb-3 flex items-center justify-between">
                  <h3 className="text-xl font-bold">Projects</h3>
                  <button className="rounded-md border border-[#d8cfbd] bg-white px-3 py-2 text-sm font-semibold">
                    View all
                  </button>
                </div>
                <div className="grid gap-3">
                  {projects.map((project) => (
                    <article className="rounded-lg border border-[#ddd4c3] bg-white p-4" key={project.name}>
                      <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                          <h4 className="font-bold">{project.name}</h4>
                          <p className="text-sm text-[#66746e]">
                            {project.status} with {project.tasks} tasks
                          </p>
                        </div>
                        <strong>{project.progress}%</strong>
                      </div>
                      <div className="mt-4 h-2 rounded-full bg-[#ede6da]">
                        <div
                          className="h-2 rounded-full bg-[#2e7a50]"
                          style={{ width: `${project.progress}%` }}
                        />
                      </div>
                    </article>
                  ))}
                </div>
              </div>

              <div className="rounded-lg border border-[#ddd4c3] bg-[#12312b] p-5 text-white">
                <h3 className="text-xl font-bold">Phase 1 architecture</h3>
                <div className="mt-5 grid gap-3">
                  {[
                    "Laravel modular monolith API",
                    "Sanctum auth with 2FA-ready security events",
                    "MySQL tenant data model with UTC scheduling",
                    "Redis queues and broadcasting-ready events",
                    "Next.js feature folders with typed API clients",
                    "Future video room columns on meetings",
                  ].map((item) => (
                    <div className="flex items-center gap-3" key={item}>
                      <span className="h-2 w-2 rounded-full bg-[#f6c35b]" />
                      <p className="text-sm text-[#e7eee9]">{item}</p>
                    </div>
                  ))}
                </div>
              </div>
            </section>
          </div>
        </section>
      </div>
    </main>
  );
}
