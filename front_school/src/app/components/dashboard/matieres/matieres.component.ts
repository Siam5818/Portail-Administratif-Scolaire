import { Component } from '@angular/core';
import { MatiereFormPayload } from '../../../models/matiere-from-playload';
import { ActivatedRoute, Router } from '@angular/router';
import { MatiereService } from '../../../services/matiere.service';
import { AuthService } from '../../../services/auth.service';

@Component({
  selector: 'app-matieres',
  templateUrl: './matieres.component.html',
  styleUrls: ['./matieres.component.css'],
})
export class MatieresComponent {
  matieres: MatiereFormPayload[] = [];
  resultatsMatieres: MatiereFormPayload[] = [];
  searchTerm: string = '';
  loading: boolean = false;
  errorMessage: string = '';

  constructor(
    private matiereService: MatiereService,
    private router: Router,
    private authService: AuthService
  ) {}

  ngOnInit(): void {
    if (this.authService.isLoggedIn()) {
      this.onGetAll();
    } else {
      this.router.navigate(['/login']);
    }
  }

  onGetAll(): void {
    this.loading = true;
    this.matiereService.getMatieres().subscribe({
      next: (data: MatiereFormPayload[]) => {
        this.matieres = data;
        this.resultatsMatieres = [];
        this.loading = false;
      },
      error: () => {
        this.errorMessage = 'Erreur lors du chargement des matières.';
        this.loading = false;
      },
    });
  }

  onResetForm(): void {
    this.searchTerm = '';
    this.onGetAll();
  }

  onSearch(): void {
    const term = this.searchTerm.trim().toLowerCase();
    if (!term) {
      this.resultatsMatieres = [];
      return;
    }

    this.loading = true;
    this.errorMessage = '';

    this.matiereService.search(term).subscribe({
      next: (matieres) => {
        this.resultatsMatieres = matieres;
        this.loading = false;
      },
      error: () => {
        this.resultatsMatieres = [];
        this.errorMessage = 'Aucune matière ne correspond à votre recherche.';
        this.loading = false;
      },
    });
  }

  onDelete(id: number): void {
    if (confirm('Voulez-vous vraiment supprimer cette matière ?')) {
      this.matiereService.deleteMatiere(id).subscribe({
        next: () => {
          this.matieres = this.matieres.filter((m) => m.id !== id);
          this.resultatsMatieres = this.resultatsMatieres.filter(
            (m) => m.id !== id
          );
        },
        error: () => {
          this.errorMessage = 'Erreur lors de la suppression.';
        },
      });
    }
  }
}
