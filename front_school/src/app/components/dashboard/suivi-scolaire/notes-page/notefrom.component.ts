import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { NoteService } from '../../../../services/note.service';
import { AuthService } from '../../../../services/auth.service';
import { Note } from '../../../../models/note';
import { Matiere } from '../../../../models/matiere';
import { MatiereService } from '../../../../services/matiere.service';

@Component({
  selector: 'app-noteform',
  templateUrl: './noteform.component.html',
  styleUrls: ['./notes-page.component.css'],
})
export class NotesFormComponent implements OnInit {
  noteForm!: FormGroup;
  noteId: number | null = null;
  isEditMode = false;
  successMessage = '';
  errorMessage = '';
  submittedForm = false;
  textButtonVOUp = 'Ajouter';
  matiereVideMessage: string | null = null;
  matieres: Matiere[] = [];

  constructor(
    private authService: AuthService,
    private routeActiv: ActivatedRoute,
    private router: Router,
    private noteService: NoteService,
    private matiereService: MatiereService
  ) {}

  ngOnInit(): void {
    if (!this.authService.isLoggedIn()) {
      this.router.navigate(['/login']);
      return;
    }

    this.initForm();
    this.noteForm
      .get('eleve_id')
      ?.valueChanges.subscribe(() => this.onEleveChange());

    const idrecup = this.routeActiv.snapshot.params['id'];
    if (idrecup) {
      this.noteId = +idrecup;
      this.isEditMode = true;
      this.textButtonVOUp = 'Modifier';
      this.getNoteById(this.noteId);
    }
  }

  initForm(): void {
    this.noteForm = new FormGroup({
      id: new FormControl<number | null>(null),
      eleve_id: new FormControl<number | null>(null, [Validators.required]),
      matiere_id: new FormControl({ value: null, disabled: true }, [
        Validators.required,
      ]),
      note: new FormControl({ value: null, disabled: true }, [
        Validators.min(0),
        Validators.max(20),
      ]),
      periode: new FormControl<string>('', [Validators.required]),
    });
  }

  get tbErreurFront() {
    return this.noteForm.controls;
  }

  getNoteById(id: number): void {
    this.noteService.getNoteById(id).subscribe({
      next: (note: Note) => {
        this.noteForm.patchValue({
          id: note.id,
          eleve_id: note.eleve.id,
          matiere_id: note.matiere.id,
          note: note.note,
          periode: note.periode,
        });

        // Charger les matières de l'élève
        this.matiereService.getMatieresByEleveId(note.eleve.id).subscribe({
          next: (data) => {
            this.matieres = data;
          },
          error: () => {
            this.errorMessage = 'Impossible de charger les matières.';
          },
        });
      },
      error: (err) => {
        this.errorMessage = 'Erreur lors du chargement de la note.';
        console.error(err);
      },
    });
  }

  onSubmit(): void {
    this.submittedForm = true;
    this.successMessage = '';
    this.errorMessage = '';

    if (this.noteForm.invalid) return;

    const payload: Note = this.noteForm.value;

    if (!this.isEditMode) {
      this.noteService.addNote(payload).subscribe({
        next: () => {
          this.successMessage = 'Note ajoutée avec succès.';
          setTimeout(
            () => this.router.navigate(['/suivi-scolaire/notes']),
            1500
          );
        },
        error: (err) => {
          this.errorMessage = 'Erreur lors de l’ajout de la note.';
          console.error(err);
        },
      });
    } else {
      this.noteService.updateNote(payload).subscribe({
        next: () => {
          this.successMessage = 'Note mise à jour avec succès.';
          setTimeout(
            () => this.router.navigate(['/suivi-scolaire/notes']),
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

  onEleveChange(): void {
    const eleveId = this.noteForm.get('eleve_id')?.value;
    const matiereControl = this.noteForm.get('matiere_id');
    const noteControl = this.noteForm.get('note');

    // Réinitialiser les champs liés
    matiereControl?.setValue(null);
    noteControl?.setValue(null);
    this.matiereVideMessage = null;

    if (eleveId) {
      this.matiereService.getMatieresByEleveId(eleveId).subscribe({
        next: (data) => {
          this.matieres = data;

          if (this.matieres.length > 0) {
            matiereControl?.enable();
            noteControl?.enable();
            this.matiereVideMessage = null;
          } else {
            matiereControl?.disable();
            noteControl?.disable();
            this.matiereVideMessage =
              'Aucune matière disponible pour cet élève.';
          }

          console.log('Matières chargées :', this.matieres);
        },
        error: () => {
          this.errorMessage = 'Impossible de charger les matières.';
          matiereControl?.disable();
          noteControl?.disable();
          this.matiereVideMessage = null;
        },
      });
    } else {
      matiereControl?.disable();
      noteControl?.disable();
      this.matiereVideMessage = null;
    }
  }

  onCancel(): void {
    this.router.navigate(['/suivi-scolaire/notes']);
  }
}
