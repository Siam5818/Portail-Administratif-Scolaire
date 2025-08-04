import { Component } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Classe } from '../../../models/classe';
import { MatiereService } from '../../../services/matiere.service';
import { ClasseService } from '../../../services/classe.service';
import { ActivatedRoute, Router } from '@angular/router';
import { AuthService } from '../../../services/auth.service';
import { Enseignant } from '../../../models/enseignant';
import { MatiereFormPayload } from '../../../models/matiere-from-playload';
import { Matiere } from '../../../models/matiere';
import { EnseignantService } from '../../../services/enseignant.service';

@Component({
  selector: 'app-matiereform',
  templateUrl: './matiereform.component.html',
  styleUrl: './matieres.component.css',
})
export class MatiereFormComponent {
  matiereForm!: FormGroup;
  classes: Classe[] = [];
  enseignants: Enseignant[] = [];
  successMessage = '';
  errorMessage = '';
  submittedForm = false;
  textButtonVOUp = 'Ajouter';

  constructor(
    private materielService: MatiereService,
    private classeService: ClasseService,
    private enseignantService: EnseignantService,
    private authService: AuthService,
    private routeActiv: ActivatedRoute,
    private router: Router
  ) {}

  ngOnInit(): void {
    if (!this.authService.isLoggedIn()) {
      this.router.navigate(['/login']);
      return;
    }

    this.matiereForm = new FormGroup({
      id: new FormControl<number | null>(null),
      nom: new FormControl('', Validators.required),
      coefficient: new FormControl('', [
        Validators.required,
        Validators.min(1),
        Validators.max(20),
      ]),
      classe_id: new FormControl('', Validators.required),
      enseignant_id: new FormControl('', Validators.required),
    });

    this.loadClasses();
    this.loadEnseignants();

    const idrecup = this.routeActiv.snapshot.params['id'];
    if (idrecup) {
      this.textButtonVOUp = 'Modifier';
      this.getMatiereById(+idrecup);
    }
  }

  get tbErreurFront() {
    return this.matiereForm.controls;
  }

  loadClasses(): void {
    this.classeService.getClasses().subscribe({
      next: (data) => (this.classes = data),
      error: (err) => {
        this.errorMessage = 'Erreur lors du chargement des classes.';
        console.error(err);
      },
    });
  }

  loadEnseignants(): void {
    this.enseignantService.getEnseignants().subscribe({
      next: (data) => (this.enseignants = data),
      error: (err) => {
        this.errorMessage = 'Erreur lors du chargement des enseignants.';
        console.error(err);
      },
    });
  }

  getMatiereById(id: number): void {
    this.materielService.getMatiereById(id).subscribe({
      next: (matiere) => {
        this.matiereForm.patchValue({
          id: matiere.id,
          nom: matiere.nom,
          coefficient: matiere.coefficient,
          classe_id: matiere.classe.id,
          enseignant_id: matiere.enseignant.id,
        });
      },
      error: (err) => {
        this.errorMessage = 'Erreur lors du chargement de la matière.';
        console.error(err);
      },
    });
  }
  onSubmit(): void {
    this.submittedForm = true;
    this.successMessage = '';
    this.errorMessage = '';

    if (this.matiereForm.invalid) return;

    const payload: Matiere = this.matiereForm.value;

    if (this.textButtonVOUp === 'Ajouter') {
      this.materielService.addMatiere(payload).subscribe({
        next: () => {
          this.successMessage = 'Matiere ajouté avec succès.';
          setTimeout(() => this.router.navigate(['gestion-matieres']), 1500);
        },
        error: (err) => {
          this.errorMessage = "Erreur lors de l’ajout d'une matiere.";
          console.error(err);
        },
      });
    } else {
      this.materielService.updateMatiere(payload).subscribe({
        next: () => {
          this.successMessage = 'Matiere mis à jour avec succès.';
          setTimeout(() => this.router.navigate(['/gestion-matieres']), 1500);
        },
        error: (err) => {
          this.errorMessage = 'Erreur lors de la mise à jour.';
          console.error(err);
        },
      });
    }
  }

  onCancel(): void {
    this.router.navigate(['/gestion-matieres']);
  }
}
