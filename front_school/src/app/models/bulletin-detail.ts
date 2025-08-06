export class BulletinDetail {
  eleve!: {
    nom: string;
    prenom: string;
    matricule: string;
    date_naissance: string;
    classe: {
      libelle: string;
    };
  };
  periode!: string;
  annee!: number;
  notes!: {
    matiere: string;
    note: number | null;
    coefficient: number;
    appreciation: string;
  }[];
  moyenne!: number;
  mention!: string;
  pdf_url!: string | null;
}
