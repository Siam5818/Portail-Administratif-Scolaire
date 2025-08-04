export interface EleveResponse {
  id: number;
  date_naissance: string | null;
  classe_id: number | null;
  document_justificatif: string | null;
  user: {
    nom: string;
    prenom: string;
    email: string;
  };
  tuteur: {
    profession?: string | null;
    telephone?: string | null;
    user: {
      nom: string;
      prenom: string;
      email: string;
    };
  };
}