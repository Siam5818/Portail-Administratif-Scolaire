import { Classe } from './classe';
import { Tuteur } from './tuteur';
import { User } from './user';

export class Eleve {
  id!: number;
  date_naissance!: String;
  document_justificatif!: String | null;
  user!: User;
  classe!: Classe;
  tuteur!: Tuteur;
}
