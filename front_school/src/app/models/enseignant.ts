import { Classe } from "./classe";
import { User } from "./user";

export class Enseignant {
  id!: number;
  specialite!: string;
  user!: User;
  classe!:Classe;
}
