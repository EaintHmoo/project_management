"use client";

import { useState } from "react";
import { Button } from "@/components/ui/Button";
import { CreateOrganizationForm } from "@/features/organizations/components/CreateOrganizationForm";
import { useCurrentOrganization } from "@/features/organizations/hooks/useCurrentOrganization";
import { useSwitchOrganization } from "@/features/organizations/hooks/useOrganizationMutations";

export function OrganizationSwitcher() {
  const { organization, organizations, isLoading } = useCurrentOrganization();
  const switchOrganizationMutation = useSwitchOrganization();
  const [isOpen, setIsOpen] = useState(false);
  const [isCreating, setIsCreating] = useState(false);

  if (isLoading) {
    return null;
  }

  return (
    <div className="relative">
      <button
        type="button"
        onClick={() => setIsOpen((v) => !v)}
        className="flex items-center gap-2 rounded-md border border-[#d8cfbd] bg-white px-3 py-2 text-sm font-semibold text-[#18201f] hover:bg-[#efe8da]"
      >
        🏢 {organization ? organization.name : "No organization"}
        <span className="text-[#9aa39c]">▾</span>
      </button>

      {isOpen && (
        <>
          <div className="fixed inset-0 z-10" onClick={() => setIsOpen(false)} />
          <div className="absolute right-0 z-20 mt-2 w-72 rounded-md border border-[#ded7ca] bg-white p-2 shadow-lg">
            <div className="grid gap-1">
              {organizations.map((org) => (
                <button
                  key={org.id}
                  type="button"
                  onClick={() => {
                    switchOrganizationMutation.mutate(String(org.id));
                    setIsOpen(false);
                  }}
                  className={`rounded-md px-3 py-2 text-left text-sm font-medium ${
                    org.id === organization?.id ? "bg-[#12312b] text-white" : "hover:bg-[#efe8da] text-[#18201f]"
                  }`}
                >
                  {org.name}
                  {org.my_role && <span className="ml-2 text-xs opacity-70">{org.my_role}</span>}
                </button>
              ))}
              {organizations.length === 0 && (
                <p className="px-3 py-2 text-sm text-[#9aa39c]">No organizations yet.</p>
              )}
            </div>

            <div className="mt-2 border-t border-[#ded7ca] pt-2">
              {isCreating ? (
                <div className="p-1">
                  <CreateOrganizationForm
                    onCreated={() => {
                      setIsCreating(false);
                      setIsOpen(false);
                    }}
                  />
                </div>
              ) : (
                <Button variant="ghost" size="sm" className="w-full justify-start" onClick={() => setIsCreating(true)}>
                  + New organization
                </Button>
              )}
            </div>
          </div>
        </>
      )}
    </div>
  );
}
