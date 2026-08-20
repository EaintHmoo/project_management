import { cn } from "@/lib/utils/cn";

export function Alert({
  variant = "error",
  className,
  ...props
}: React.HTMLAttributes<HTMLDivElement> & { variant?: "error" | "success" }) {
  return (
    <div
      role="alert"
      className={cn(
        "rounded-md border px-3 py-2 text-sm font-medium",
        variant === "error" && "border-[#f4c9c0] bg-[#f4e7e3] text-[#8f321f]",
        variant === "success" && "border-[#bfe3cd] bg-[#e9f5ee] text-[#1d6b42]",
        className,
      )}
      {...props}
    />
  );
}
