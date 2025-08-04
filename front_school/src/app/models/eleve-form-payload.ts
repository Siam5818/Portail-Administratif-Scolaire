export class EleveFormPayload {
  id!: number;
  nom!: string;
  prenom!: string;
  email!: string;
  date_naissance!: string | null;
  classe_id!: number | null;
  document_justificatif!: string | null;
  tuteur!: {
    nom: string;
    prenom: string;
    email: string;
    profession?: string | null;
    telephone?: string | null;
  };
}
