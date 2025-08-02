import { Classe } from "./classe";
import { User } from "./user";

export class Matiere {
  id!: number;
  nom!: string;
  coefficient!: number;
  classe!: Classe;
  enseignant!: User;
}
