import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { BulletinsPageComponent } from './bulletins-page/bulletins-page.component';
import { NotesPageComponent } from './notes-page/notes-page.component';
import { SuiviScolaireComponent } from './suivi-scolaire.component';

@NgModule({
  declarations: [
    SuiviScolaireComponent,
    NotesPageComponent,
    BulletinsPageComponent,
  ],
  imports: [CommonModule],
})
export class SuiviScolaireModule {}
