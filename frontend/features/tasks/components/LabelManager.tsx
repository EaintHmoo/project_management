"use client";

import { useState } from "react";
import { Button } from "@/components/ui/Button";
import { FieldError } from "@/components/ui/FieldError";
import { Input } from "@/components/ui/Input";
import { useCreateLabel, useDeleteLabel, useLabels } from "@/features/tasks/hooks/useLabels";
import { createLabelSchema } from "@/features/tasks/schemas/labelSchema";

export function LabelManager({
  organizationId,
  selectedIds,
  onToggle,
}: {
  organizationId: string;
  selectedIds: number[];
  onToggle: (labelId: number) => void;
}) {
  const { data: labels } = useLabels(organizationId);
  const createLabelMutation = useCreateLabel(organizationId);
  const deleteLabelMutation = useDeleteLabel(organizationId);
  const [name, setName] = useState("");
  const [color, setColor] = useState("#4f5f58");
  const [error, setError] = useState<string | undefined>();

  const onAddLabel = () => {
    const result = createLabelSchema.safeParse({ name, color });

    if (!result.success) {
      setError(result.error.issues[0]?.message);
      return;
    }

    setError(undefined);
    createLabelMutation.mutate(result.data, { onSuccess: () => setName("") });
  };

  return (
    <div className="grid gap-2">
      <div className="flex flex-wrap gap-2">
        {(labels ?? []).map((label) => {
          const isSelected = selectedIds.includes(label.id);

          return (
            <button
              key={label.id}
              type="button"
              onClick={() => onToggle(label.id)}
              className={`group inline-flex items-center gap-1.5 rounded-md border px-2 py-1 text-xs font-semibold transition-colors ${
                isSelected ? "border-[#12312b] bg-[#12312b] text-white" : "border-[#d8cfbd] bg-white text-[#4f5f58]"
              }`}
            >
              <span className="h-2 w-2 rounded-full" style={{ backgroundColor: label.color }} />
              {label.name}
              <span
                role="button"
                tabIndex={-1}
                onClick={(event) => {
                  event.stopPropagation();
                  if (window.confirm(`Delete label "${label.name}"?`)) {
                    deleteLabelMutation.mutate(label.id);
                  }
                }}
                className="ml-1 hidden text-[10px] opacity-70 hover:opacity-100 group-hover:inline"
              >
                ✕
              </span>
            </button>
          );
        })}
      </div>

      <div className="flex items-center gap-2">
        <Input
          value={name}
          onChange={(e) => setName(e.target.value)}
          placeholder="New label"
          className="h-9 max-w-[160px]"
        />
        <input
          type="color"
          value={color}
          onChange={(e) => setColor(e.target.value)}
          className="h-9 w-9 cursor-pointer rounded-md border border-[#d8cfbd] bg-white p-1"
        />
        <Button type="button" size="sm" variant="secondary" isLoading={createLabelMutation.isPending} onClick={onAddLabel}>
          Add
        </Button>
      </div>
      <FieldError message={error} />
    </div>
  );
}
