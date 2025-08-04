import { Component, OnInit } from '@angular/core';
import {
  FormGroup,
  FormControl,
  Validators,
  AbstractControl,
  ValidationErrors,
} from '@angular/forms';
import { EleveService } from '../../../../services/eleve.service';
import { ClasseService } from '../../../../services/classe.service';
import { AuthService } from '../../../../services/auth.service';
import { ActivatedRoute, Router } from '@angular/router';
import { EleveFormPayload } from '../../../../models/eleve-form-payload';
import { Classe } from '../../../../models/classe';
import { EleveResponse } from '../../../../models/eleve-response';

@Component({
  selector: 'app-eleveform',
  templateUrl: './eleveform.component.html',
})
export class EleveFormComponent implements OnInit {
  eleveForm!: FormGroup;
  classes: Classe[] = [];
  successMessage = '';
  errorMessage = '';
  submittedForm = false;
  textButtonVOUp = 'Ajouter';
  today = new Date().toISOString().split('T')[0];

  constructor(
    private eleveService: EleveService,
    private classeService: ClasseService,
    private authService: AuthService,
    private routeActiv: ActivatedRoute,
    private router: Router
  ) {}

  ngOnInit(): void {
    // Vérifier si l'utilisateur est connecté avant d'initialiser le formulaire
    if (!this.authService.isLoggedIn()) {
      this.router.navigate(['/login']);
      return;
    }

    this.initForm();
    this.loadClasses();

    const idrecup = this.routeActiv.snapshot.params['id'];
    if (idrecup) {
      this.textButtonVOUp = 'Modifier';
      this.getEleveById(idrecup);
    }
  }

  initForm(): void {
    this.eleveForm = new FormGroup({
      id: new FormControl<number | null>(null),
      nom: new FormControl('', [Validators.required]),
      prenom: new FormControl('', [Validators.required]),
      email: new FormControl('', [Validators.required, Validators.email]),
      date_naissance: new FormControl('', [
        Validators.required,
        this.pastDateValidator,
      ]),
      classe_id: new FormControl(null, [Validators.required]),
      document_justificatif: new FormControl(''),
      tuteur: new FormGroup({
        nom: new FormControl('', [Validators.required]),
        prenom: new FormControl('', [Validators.required]),
        email: new FormControl('', [Validators.required, Validators.email]),
        profession: new FormControl(''),
        telephone: new FormControl(''),
      }),
    });
  }

  get tbErreurFront() {
    return this.eleveForm.controls;
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

  getEleveById(id: number): void {
    this.eleveService.getEleveById(id).subscribe({
      next: (data: EleveResponse) => {
        this.eleveForm.patchValue({
          id: data.id ?? null,
          nom: data.user?.nom ?? '',
          prenom: data.user?.prenom ?? '',
          email: data.user?.email ?? '',
          date_naissance: data.date_naissance?.split('T')[0] ?? '',
          classe_id: data.classe_id ?? null,
          document_justificatif: data.document_justificatif ?? '',
        });

        const tuteurGroup = this.eleveForm.get('tuteur') as FormGroup;

        if (data.tuteur?.user) {
          tuteurGroup.patchValue({
            nom: data.tuteur.user.nom ?? '',
            prenom: data.tuteur.user.prenom ?? '',
            email: data.tuteur.user.email ?? '',
            profession: data.tuteur.profession ?? '',
            telephone: data.tuteur.telephone ?? '',
          });
        } else {
          tuteurGroup.reset();
        }
        console.log('FormGroup tuteur après patch:', tuteurGroup.value);
      },
      error: (err) => {
        console.error('Erreur lors du chargement de l’élève:', err);
      },
    });
  }

  onSubmit(): void {
    this.submittedForm = true;
    this.successMessage = '';
    this.errorMessage = '';

    if (this.eleveForm.invalid) return;

    const payload: EleveFormPayload = this.eleveForm.value;

    if (this.textButtonVOUp === 'Ajouter') {
      this.eleveService.addEleve(payload).subscribe({
        next: () => {
          this.successMessage = 'Élève ajouté avec succès.';
          setTimeout(
            () => this.router.navigate(['/gestion-utilisateurs/eleves']),
            1500
          );
        },
        error: (err) => {
          this.errorMessage = 'Erreur lors de l’ajout de l’élève.';
          console.error(err);
        },
      });
    } else {
      this.eleveService.updateEleve(payload).subscribe({
        next: () => {
          this.successMessage = 'Élève mis à jour avec succès.';
          setTimeout(
            () => this.router.navigate(['/gestion-utilisateurs/eleves']),
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

  pastDateValidator(control: AbstractControl): ValidationErrors | null {
    const today = new Date();
    const selectedDate = new Date(control.value);
    today.setHours(0, 0, 0, 0);
    selectedDate.setHours(0, 0, 0, 0);
    return selectedDate <= today ? null : { pastDate: true };
  }

  onCancel(): void {
    this.router.navigate(['/gestion-utilisateurs/eleves']);
  }
}
