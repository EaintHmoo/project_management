"use client";

import { QueryClientProvider } from "@tanstack/react-query";
import { useEffect, useState } from "react";
import { createQueryClient } from "@/lib/query/queryClient";
import { useAuthStore } from "@/stores/authStore";
import { useOrganizationStore } from "@/stores/organizationStore";

export function Providers({ children }: { children: React.ReactNode }) {
  const [queryClient] = useState(createQueryClient);
  const hydrate = useAuthStore((state) => state.hydrate);
  const hydrateOrganization = useOrganizationStore((state) => state.hydrate);

  useEffect(() => {
    hydrate();
    hydrateOrganization();
  }, [hydrate, hydrateOrganization]);

  return <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>;
}
