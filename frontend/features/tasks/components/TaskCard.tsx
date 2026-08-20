import type { Task } from "@/features/tasks/types/task";

const PRIORITY_COLORS: Record<Task["priority"], string> = {
  low: "bg-[#edf2ef] text-[#3d5d51]",
  medium: "bg-[#f3ecd8] text-[#8a6a1f]",
  high: "bg-[#f4e3da] text-[#a35624]",
  urgent: "bg-[#f4e7e3] text-[#8f321f]",
};

export function TaskCard({ task, onSelect }: { task: Task; onSelect: (task: Task) => void }) {
  return (
    <article
      onClick={() => onSelect(task)}
      className="cursor-pointer rounded-lg border border-[#ded7ca] bg-white p-3 transition-colors hover:border-[#12312b]"
    >
      <div className="flex items-start justify-between gap-2">
        <h5 className="text-sm font-bold leading-5">{task.title}</h5>
        <span className={`shrink-0 rounded-md px-1.5 py-0.5 text-[10px] font-semibold uppercase ${PRIORITY_COLORS[task.priority]}`}>
          {task.priority}
        </span>
      </div>

      {task.labels.length > 0 && (
        <div className="mt-2 flex flex-wrap gap-1">
          {task.labels.map((label) => (
            <span key={label.id} className="rounded-md bg-[#edf2ef] px-2 py-0.5 text-xs text-[#3d5d51]">
              {label.name}
            </span>
          ))}
        </div>
      )}

      <div className="mt-3 flex items-center justify-between text-xs text-[#66746e]">
        <span>{task.assignee ? task.assignee.name : "Unassigned"}</span>
        <div className="flex items-center gap-2">
          {task.due_at && <span>{new Date(task.due_at).toLocaleDateString()}</span>}
          {!!task.comments_count && <span>💬 {task.comments_count}</span>}
        </div>
      </div>
    </article>
  );
}
