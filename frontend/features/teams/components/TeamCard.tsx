import Link from "next/link";
import { Card } from "@/components/ui/Card";
import type { Team } from "@/features/teams/types/team";

export function TeamCard({ team }: { team: Team }) {
  return (
    <Link href={`/teams/${team.id}`}>
      <Card className="flex h-full flex-col justify-between transition-colors hover:border-[#12312b]">
        <div>
          <h3 className="text-lg font-bold text-[#18201f]">{team.name}</h3>
          {team.description && <p className="mt-1 line-clamp-2 text-sm text-[#66746e]">{team.description}</p>}
        </div>
        <p className="mt-4 text-xs font-medium text-[#9aa39c]">
          {team.projects_count ?? 0} project{team.projects_count === 1 ? "" : "s"}
        </p>
      </Card>
    </Link>
  );
}
