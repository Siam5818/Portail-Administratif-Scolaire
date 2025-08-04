import { Component } from '@angular/core';
import { Enseignant } from '../../../../models/enseignant';
import { Router } from '@angular/router';
import { AuthService } from '../../../../services/auth.service';
import { EnseignantService } from '../../../../services/enseignant.service';

@Component({
  selector: 'app-enseignant',
  templateUrl: './enseignant.component.html',
  styleUrls: ['./enseignant.component.css'],
})
export class EnseignantComponent {
  enseignants: Enseignant[] = [];
  motcle: string = '';
  loading = false;
  resultatEnseignant: Enseignant[] = [];
  errorMessage = '';
  searchTerm = '';

  constructor(
    private enseignantService: EnseignantService,
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
    this.errorMessage = '';
    this.enseignantService.getEnseignants().subscribe({
      next: (data) => {
        this.enseignants = data;
        this.resultatEnseignant = [];
        this.loading = false;
      },
      error: (err) => {
        this.errorMessage = 'Impossible de charger les enseignants.';
        this.loading = false;
        console.error(err);
      },
    });
  }

  onSearch(): void {
    const motCle = this.searchTerm.trim();
    if (!motCle) {
      this.onGetAll();
      return;
    }

    this.loading = true;
    this.errorMessage = '';
    this.enseignantService.search(motCle).subscribe({
      next: (eleves) => {
        this.resultatEnseignant = eleves;
        this.loading = false;
      },
      error: () => {
        this.resultatEnseignant = [];
        this.errorMessage = 'Aucun enseignant ne correspond à votre recherche.';
        this.loading = false;
      },
    });
  }

  onResetForm(): void {
    this.searchTerm = '';
    this.onGetAll();
  }

  onDelete(id: number): void {
    const confirmation = confirm(
      'Êtes-vous sûr de vouloir supprimer cet enseignant ?'
    );
    if (!confirmation) return;

    this.loading = true;
    this.errorMessage = '';
    this.enseignantService.deleteEnseignant(id).subscribe({
      next: () => {
        this.enseignants = this.enseignants.filter((e) => e.id !== id);
        this.resultatEnseignant = this.resultatEnseignant.filter(
          (e) => e.id !== id
        );
        this.loading = false;
        alert('Enseignant supprimé avec succès.');
      },
      error: (err) => {
        this.errorMessage = "Échec de la suppression de l'enseignant.";
        this.loading = false;
        console.error(err);
      },
    });
  }
}
