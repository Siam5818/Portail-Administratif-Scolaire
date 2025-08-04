import { Component, Input } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { AuthService } from '../../../services/auth.service';
import { ClasseService } from '../../../services/classe.service';

@Component({
  selector: 'app-classeform',
  templateUrl: './classeform.component.html',
  styleUrls: ['./classes.component.css'],
})
export class ClasseFormComponent {
  classeForm!: FormGroup;
  successMessage = '';
  errorMessage = '';
  submittedForm = false;
  textButtonVOUp = 'Ajouter';

  constructor(
    private classeService: ClasseService,
    private authService: AuthService,
    private routeActiv: ActivatedRoute,
    private router: Router
  ) {}

  ngOnInit(): void {
    if (!this.authService.isLoggedIn()) {
      this.router.navigate(['/login']);
      return;
    }

    this.classeForm = new FormGroup({
      id: new FormControl<number | null>(null),
      libelle: new FormControl('', [
        Validators.required,
        Validators.minLength(2),
      ]),
      niveau: new FormControl('', [Validators.required]),
    });

    const idrecup = this.routeActiv.snapshot.params['id'];
    if (idrecup) {
      this.textButtonVOUp = 'Modifier';
      this.getClasseById(+idrecup);
    }
  }

  get tbErreurFront() {
    return this.classeForm.controls;
  }

  onSubmit(): void {
    this.submittedForm = true;
    if (this.classeForm.invalid) {
      this.errorMessage = 'Veuillez remplir tous les champs requis.';
      return;
    }

    const formData = this.classeForm.value;

    if (formData.id) {
      this.classeService.updateClasse(formData).subscribe({
        next: () => {
          this.successMessage = 'Classe modifiée avec succès.';
          this.router.navigate(['/gestion-classes']);
        },
        error: () => {
          this.errorMessage = 'Erreur lors de la modification.';
        },
      });
    } else {
      this.classeService.addClasse(formData).subscribe({
        next: () => {
          this.successMessage = 'Classe ajoutée avec succès.';
          this.router.navigate(['/gestion-classes']);
        },
        error: () => {
          this.errorMessage = 'Erreur lors de l’ajout.';
        },
      });
    }
  }

  getClasseById(id: number): void {
    this.classeService.getClasseById(id).subscribe({
      next: (classe) => {
        this.classeForm.patchValue(classe);
      },
      error: () => {
        this.errorMessage = 'Impossible de charger la classe.';
      },
    });
  }

  onCancel(): void {
    this.router.navigate(['/gestion-classes']);
  }
}
