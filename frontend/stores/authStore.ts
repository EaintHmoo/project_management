import { create } from "zustand";
import { clearToken, getToken, setToken } from "@/lib/auth/token";

export interface AuthUser {
  id: number;
  name: string;
  email: string;
  email_verified_at: string | null;
}

interface AuthState {
  token: string | null;
  user: AuthUser | null;
  isHydrated: boolean;
  hydrate: () => void;
  setSession: (token: string, user: AuthUser) => void;
  setUser: (user: AuthUser) => void;
  clearSession: () => void;
}

export const useAuthStore = create<AuthState>((set) => ({
  token: null,
  user: null,
  isHydrated: false,
  hydrate: () => set({ token: getToken(), isHydrated: true }),
  setSession: (token, user) => {
    setToken(token);
    set({ token, user });
  },
  setUser: (user) => set({ user }),
  clearSession: () => {
    clearToken();
    set({ token: null, user: null });
  },
}));
