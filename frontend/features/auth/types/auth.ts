import type { AuthUser } from "@/stores/authStore";

export interface RegisteredUser {
  id: number;
  name: string;
  email: string;
  email_verified_at: string | null;
}

export interface LoginChallenge {
  challenge_id: number;
  expires_at: string;
}

export interface TwoFactorVerifyResult {
  user: AuthUser;
  token: string;
}
