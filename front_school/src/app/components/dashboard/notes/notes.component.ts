import { Component, Input } from '@angular/core';

interface NoteData {
  eleve?: {
    nom: string;
    prenom: string;
    classe: string;
  };
  moyenne?: number;
  periode_complete?: string;
  status?: string;
  message?: string;
}

@Component({
  selector: 'app-notes',
  templateUrl: './notes.component.html',
  styleUrl: './notes.component.css',
})
export class NotesComponent {
  @Input() data: NoteData | null = null;
}
