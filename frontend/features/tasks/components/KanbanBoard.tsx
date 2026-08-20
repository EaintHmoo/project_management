"use client";

import { useState } from "react";
import { Card } from "@/components/ui/Card";
import { CreateTaskForm } from "@/features/tasks/components/CreateTaskForm";
import { TaskCard } from "@/features/tasks/components/TaskCard";
import { TaskDetailPanel } from "@/features/tasks/components/TaskDetailPanel";
import { useMoveTask } from "@/features/tasks/hooks/useTaskMutations";
import { useTasks } from "@/features/tasks/hooks/useTasks";
import { TASK_STATUSES, type Task, type TaskStatus } from "@/features/tasks/types/task";
import { Button } from "@/components/ui/Button";

export function KanbanBoard({ organizationId, projectId }: { organizationId: string; projectId: number }) {
  const { data: board, isLoading, isError } = useTasks(projectId);
  const moveTaskMutation = useMoveTask(projectId);
  const [selectedTask, setSelectedTask] = useState<Task | null>(null);
  const [isCreating, setIsCreating] = useState(false);

  if (isLoading) {
    return <p className="text-sm text-[#66746e]">Loading tasks…</p>;
  }

  if (isError || !board) {
    return (
      <Card className="border-[#f4c9c0] bg-[#f4e7e3] text-[#8f321f]">Could not load tasks for this project.</Card>
    );
  }

  const moveToColumn = (task: Task, targetStatus: TaskStatus) => {
    const targetColumnCount = board.kanban[targetStatus]?.length ?? 0;
    moveTaskMutation.mutate({ taskId: task.id, status: targetStatus, position: targetColumnCount });
  };

  return (
    <div className="grid gap-4">
      <div className="flex items-center justify-between">
        <h3 className="text-xl font-bold">Task board</h3>
        <Button size="sm" variant={isCreating ? "secondary" : "primary"} onClick={() => setIsCreating((v) => !v)}>
          {isCreating ? "Cancel" : "New task"}
        </Button>
      </div>

      {isCreating && (
        <Card>
          <CreateTaskForm
            organizationId={organizationId}
            projectId={projectId}
            onCreated={() => setIsCreating(false)}
          />
        </Card>
      )}

      <div className="grid gap-4 xl:grid-cols-4">
        {TASK_STATUSES.map((column, columnIndex) => {
          const tasks = board.kanban[column.key] ?? [];

          return (
            <div key={column.key} className="rounded-lg border border-[#ddd4c3] bg-[#fffcf4] p-3">
              <div className="mb-3 flex items-center justify-between">
                <h4 className="font-semibold">{column.label}</h4>
                <span className="rounded-md bg-[#ece3d2] px-2 py-1 text-xs font-semibold">{tasks.length}</span>
              </div>

              <div className="grid gap-3">
                {tasks.map((task) => (
                  <div key={task.id} className="grid gap-1.5">
                    <TaskCard task={task} onSelect={setSelectedTask} />
                    <div className="flex justify-between px-1 text-xs text-[#9aa39c]">
                      <button
                        type="button"
                        disabled={columnIndex === 0}
                        onClick={() => moveToColumn(task, TASK_STATUSES[columnIndex - 1].key)}
                        className="hover:text-[#12312b] disabled:cursor-not-allowed disabled:opacity-30"
                      >
                        ← Move back
                      </button>
                      <button
                        type="button"
                        disabled={columnIndex === TASK_STATUSES.length - 1}
                        onClick={() => moveToColumn(task, TASK_STATUSES[columnIndex + 1].key)}
                        className="hover:text-[#12312b] disabled:cursor-not-allowed disabled:opacity-30"
                      >
                        Move forward →
                      </button>
                    </div>
                  </div>
                ))}
                {tasks.length === 0 && <p className="text-xs text-[#9aa39c]">No tasks</p>}
              </div>
            </div>
          );
        })}
      </div>

      {selectedTask && (
        <TaskDetailPanel
          organizationId={organizationId}
          projectId={projectId}
          task={selectedTask}
          onClose={() => setSelectedTask(null)}
        />
      )}
    </div>
  );
}
