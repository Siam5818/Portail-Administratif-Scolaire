export class MatiereFormPayload {
  id!: number;
  nom!: string;
  coefficient!: number;
  classe_id!: number;
  enseignant_id!: number;
  classe!: {
    id: number;
    libelle: string;
    niveau: string;
  };
  enseignant!: {
    id: number;
    specialite: string;
    user: {
      nom: string;
      prenom: string;
      email: string;
    };
  };
}
