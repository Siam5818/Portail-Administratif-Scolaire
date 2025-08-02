export class BulletinDetail {
  eleve!: {
    nom: string;
    classe: string;
  };
  periode!: string;
  annee!: number;
  notes!: {
    matiere: string;
    note: number;
    coefficient: number;
  }[];
  moyenne!: number;
  mention!: string;
  pdf!: string | null;
}
