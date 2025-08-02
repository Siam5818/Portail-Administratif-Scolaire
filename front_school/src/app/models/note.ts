import { Eleve } from "./eleve";
import { Matiere } from "./matiere";

export class Note {
  id!: number;
  note!: number | null;
  periode!: string;
  eleve!: Eleve;
  matiere!: Matiere;
}
