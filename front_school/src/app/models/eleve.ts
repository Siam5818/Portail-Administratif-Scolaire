import { Classe } from './classe';
import { Tuteur } from './tuteur';
import { User } from './user';

export class Eleve {
  id!: number;
  date_naissance!: string;
  document_justificatif!: string | null;
  user!: User;
  classe!: Classe;
  tuteur!: Tuteur;
}
