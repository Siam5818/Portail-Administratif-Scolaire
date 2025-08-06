import { Component, OnInit } from '@angular/core';
import { EleveAnnotationStatus } from '../../../../models/eleve-annotation-status';
import { EleveService } from '../../../../services/eleve.service';
import { ActivatedRoute, Router } from '@angular/router';
import { AuthService } from '../../../../services/auth.service';
import { Eleve } from '../../../../models/eleve';

@Component({
  selector: 'app-bulletins-page',
  templateUrl: './bulletins-page.component.html',
  styleUrls: ['./bulletins-page.component.css'],
})
export class BulletinsPageComponent implements OnInit {
  annotationStatusList: EleveAnnotationStatus[] = [];
  searchTerm: string = '';
  errorMessage: string = '';
  loading: boolean = false;
  dataLoaded: boolean = false;

  constructor(
    private eleveService: EleveService,
    private router: Router,
    private authService: AuthService
  ) {}

  ngOnInit(): void {
    if (this.authService.isLoggedIn()) {
      this.onGeting();
    } else {
      this.router.navigate(['/login']);
    }
  }

  onGeting(): void {
    this.eleveService.getAnnotationStatus().subscribe({
      next: (data) => {
        this.annotationStatusList = data;
        this.dataLoaded = true;
        this.loading = false;
        console.log('Résultats:', this.annotationStatusList);
      },
      error: (err) => {
        this.errorMessage = 'Erreur lors du chargement des annotations';
        console.error(this.errorMessage, err);
        this.loading = false;
      },
    });
  }

  onSearch(): void {
    if (this.searchTerm.trim()) {
      this.loading = true;
      this.eleveService.searchAnnotationStatus(this.searchTerm).subscribe({
        next: (data: EleveAnnotationStatus[]) => {
          this.annotationStatusList = data;
          this.loading = false;
        },
        error: (err) => {
          this.errorMessage = 'Erreur lors de la recherche.';
          console.error(this.errorMessage, err);
          this.loading = false;
        },
      });
    }
  }

  onResetForm(): void {
    this.searchTerm = '';
    this.errorMessage = '';
    this.onGeting();
  }

  genererBulletin(eleve: EleveAnnotationStatus): void {
    this.router.navigate(['/suivi-scolaire/bulletins/generer', eleve.eleve_id]);
  }

  bulletinGenerable(eleve: any): boolean {
    return (
      eleve.notes_renseignees === eleve.total_matieres &&
      eleve.total_matieres > 0
    );
  }
}
