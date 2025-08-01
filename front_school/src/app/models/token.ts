import { User } from "./user";

export class TokenResponse {
  access_token!: string;
  token_type!: string;
  user!: User;
}
