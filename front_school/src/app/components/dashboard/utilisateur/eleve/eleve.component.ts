import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Eleve } from '../../../../models/eleve';
import { EleveService } from '../../../../services/eleve.service';

@Component({
  selector: 'app-eleve-list',
  templateUrl: './eleve.component.html',
  styleUrls: ['./eleve.component.css'],
})
export class EleveComponent implements OnInit {
  eleves: Eleve[] = [];
  resultatsEleves: Eleve[] = [];
  loading = false;
  errorMessage = '';
  searchTerm = '';

  constructor(private eleveService: EleveService, private router: Router) {}

  ngOnInit(): void {
    this.onGetAll();
  }

  onGetAll(): void {
    this.loading = true;
    this.errorMessage = '';
    this.eleveService.getEleves().subscribe({
      next: (data) => {
        this.eleves = data;
        this.resultatsEleves = [];
        this.loading = false;
      },
      error: (err) => {
        this.errorMessage = 'Impossible de charger les élèves.';
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
    this.eleveService.searchEleve(motCle).subscribe({
      next: (eleves) => {
        this.resultatsEleves = eleves;
        this.loading = false;
      },
      error: () => {
        this.resultatsEleves = [];
        this.errorMessage = 'Aucun élève ne correspond à votre recherche.';
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
      'Êtes-vous sûr de vouloir supprimer cet élève ?'
    );
    if (!confirmation) return;

    this.loading = true;
    this.errorMessage = '';
    this.eleveService.deleteEleve(id).subscribe({
      next: () => {
        this.eleves = this.eleves.filter((e) => e.id !== id);
        this.resultatsEleves = this.resultatsEleves.filter((e) => e.id !== id);
        this.loading = false;
        alert('Élève supprimé avec succès.');
      },
      error: (err) => {
        this.errorMessage = "Échec de la suppression de l'élève.";
        this.loading = false;
        console.error(err);
      },
    });
  }
}
