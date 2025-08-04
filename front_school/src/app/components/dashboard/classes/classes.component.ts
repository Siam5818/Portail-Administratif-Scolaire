import { Component } from '@angular/core';
import { Classe } from '../../../models/classe';
import { ClasseService } from '../../../services/classe.service';
import { Router } from '@angular/router';
import { AuthService } from '../../../services/auth.service';

@Component({
  selector: 'app-classes',
  templateUrl: './classes.component.html',
  styleUrls: ['./classes.component.css'],
})
export class ClassesComponent {
  classes: Classe[] = [];
  resultatsClasse: Classe[] = [];
  searchTerm: string = '';
  loading: boolean = false;
  errorMessage: string = '';

  constructor(
    private classeService: ClasseService,
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

    this.classeService.getClasses().subscribe({
      next: (data) => {
        this.classes = data;
        this.resultatsClasse = data;
        this.loading = false;
      },
      error: () => {
        this.errorMessage = 'Erreur lors du chargement des classes.';
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
      this.resultatsClasse = [];
      return;
    }

    this.loading = true;
    this.errorMessage = '';

    this.classeService.search(term).subscribe({
      next: (classes) => {
        this.resultatsClasse = classes;
        this.loading = false;
      },
      error: () => {
        this.resultatsClasse = [];
        this.errorMessage = 'Aucune classe ne correspond à votre recherche.';
        this.loading = false;
      },
    });
  }

  onDelete(id: number): void {
    if (confirm('Voulez-vous vraiment supprimer cette classe ?')) {
      this.classeService.deleteClasse(id).subscribe({
        next: () => {
          this.classes = this.classes.filter((m) => m.id !== id);
          this.resultatsClasse = this.resultatsClasse.filter(
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
