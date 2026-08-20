"use client";

import { Button } from "@/components/ui/Button";
import { Card } from "@/components/ui/Card";
import { useAcceptInvitation, useDeclineInvitation, useMyInvitations } from "@/features/organizations/hooks/useInvitations";

export function InvitationsView() {
  const { data: invitations, isLoading } = useMyInvitations();
  const acceptMutation = useAcceptInvitation();
  const declineMutation = useDeclineInvitation();

  if (isLoading) {
    return <p className="text-sm text-[#66746e]">Loading invitations…</p>;
  }

  return (
    <div className="grid gap-6">
      <h2 className="text-xl font-bold">Invitations</h2>

      {invitations && invitations.length > 0 ? (
        <div className="grid gap-3">
          {invitations.map((invitation) => (
            <Card key={invitation.id} className="flex flex-wrap items-center justify-between gap-3">
              <div>
                <p className="font-medium">Organization #{invitation.organization_id}</p>
                <p className="text-xs text-[#66746e]">Invited as {invitation.role}</p>
              </div>
              <div className="flex items-center gap-2">
                <Button
                  size="sm"
                  isLoading={acceptMutation.isPending}
                  onClick={() => acceptMutation.mutate(invitation.id)}
                >
                  Accept
                </Button>
                <Button
                  size="sm"
                  variant="secondary"
                  isLoading={declineMutation.isPending}
                  onClick={() => declineMutation.mutate(invitation.id)}
                >
                  Decline
                </Button>
              </div>
            </Card>
          ))}
        </div>
      ) : (
        <Card>
          <p className="text-sm text-[#66746e]">No pending invitations.</p>
        </Card>
      )}
    </div>
  );
}
