import { Component } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Classe } from '../../../../models/classe';
import { ClasseService } from '../../../../services/classe.service';
import { EnseignantService } from '../../../../services/enseignant.service';
import { EnseignantFormPayload } from '../../../../models/enseignant-form-payload';
import { FormControl, FormGroup, Validators } from '@angular/forms';

@Component({
  selector: 'app-enseignantform',
  templateUrl: './enseignantform.component.html',
  styleUrls: ['./enseignant.component.css'],
})
export class EnseignantFormComponent {
  enseignantForm!: FormGroup;
  classes: Classe[] = [];
  successMessage = '';
  errorMessage = '';
  submittedForm = false;
  textButtonVOUp = 'Ajouter';

  constructor(
    private enseignantService: EnseignantService,
    private classeService: ClasseService,
    private routeActiv: ActivatedRoute,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.initForm();
    this.loadClasses();

    const idrecup = this.routeActiv.snapshot.params['id'];
    if (idrecup) {
      this.textButtonVOUp = 'Modifier';
      this.getEnseignantById(+idrecup);
    }
  }

  initForm(): void {
    this.enseignantForm = new FormGroup({
      id: new FormControl<number | null>(null),
      nom: new FormControl('', [Validators.required, Validators.minLength(2)]),
      prenom: new FormControl('', [
        Validators.required,
        Validators.minLength(2),
      ]),
      email: new FormControl('', [Validators.required, Validators.email]),
      specialite: new FormControl('', [Validators.required]),
      classe_id: new FormControl(null, [Validators.required]),
    });
  }

  get tbErreurFront() {
    return this.enseignantForm.controls;
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

  getEnseignantById(id: number): void {
    this.enseignantService.getEnseignantById(id).subscribe({
      next: (data) => {
        this.enseignantForm.patchValue({
          id: data.id ?? null,
          nom: data.user?.nom ?? '',
          prenom: data.user?.prenom ?? '',
          email: data.user?.email ?? '',
          specialite: data.specialite ?? '',
          classe_id: data.classe?.id ?? null,
        });
      },
      error: (err) => {
        console.error('Erreur lors du chargement de l’enseignant:', err);
      },
    });
  }

  onSubmit(): void {
    this.submittedForm = true;
    this.successMessage = '';
    this.errorMessage = '';

    if (this.enseignantForm.invalid) return;

    const payload: EnseignantFormPayload = this.enseignantForm.value;

    if (this.textButtonVOUp === 'Ajouter') {
      this.enseignantService.addEnseignant(payload).subscribe({
        next: (response) => {
          this.successMessage = `Enseignant ajouté avec succès.`;
          setTimeout(
            () => this.router.navigate(['/gestion-utilisateurs/enseignants']),
            1500
          );
        },
        error: (err) => {
          this.errorMessage = 'Erreur lors de l’ajout de l’enseignant.';
          console.error(err);
        },
      });
    } else {
      this.enseignantService.updateEnseignant(payload).subscribe({
        next: () => {
          this.successMessage = 'Enseignant mis à jour avec succès.';
          setTimeout(
            () => this.router.navigate(['/gestion-utilisateurs/enseignants']),
            1500
          );
        },
        error: (err) => {
          this.errorMessage = 'Erreur lors de la mise à jour.';
          console.error(err);
        },
      });
    }
  }

  onCancel(): void {
    this.router.navigate(['/gestion-utilisateurs/enseignants']);
  }
}
