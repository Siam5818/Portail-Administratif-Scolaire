import { Component, OnInit } from '@angular/core';
import { NoteService } from '../../../../services/note.service';
import { Note } from '../../../../models/note';

@Component({
  selector: 'app-notes-page',
  templateUrl: './notes-page.component.html',
  styleUrl: './notes-page.component.css',
})
export class NotesPageComponent implements OnInit {
  notes: Note[] = [];
  loading = false;
  errorMessage = '';
  filters = {
    eleve_id: undefined,
    matiere_id: undefined,
    periode: '',
  };

  constructor(private noteService: NoteService) {}

  ngOnInit(): void {
    this.loadNotes();
  }

  loadNotes(): void {
    this.loading = true;
    this.noteService.getNotes().subscribe({
      next: (data) => {
        this.notes = data;
        this.loading = false;
      },
      error: (err) => {
        this.errorMessage = 'Erreur lors du chargement des notes.';
        this.loading = false;
      },
    });
  }

  searchNotes(): void {
    this.loading = true;
    this.noteService.search(this.filters).subscribe({
      next: (data) => {
        this.notes = data;
        this.loading = false;
      },
      error: (err) => {
        this.errorMessage = 'Erreur lors de la recherche.';
        this.loading = false;
      },
    });
  }

  resetFilters(): void {
    this.filters = {
      eleve_id: undefined,
      matiere_id: undefined,
      periode: '',
    };
    this.errorMessage = '';
    this.loadNotes();
  }

  deleteNote(id: number): void {
    if (confirm('Confirmer la suppression ?')) {
      this.noteService.deleteNote(id).subscribe({
        next: () => {
          this.notes = this.notes.filter((n) => n.id !== id);
        },
        error: () => {
          this.errorMessage = 'Erreur lors de la suppression.';
        },
      });
    }
  }
}
