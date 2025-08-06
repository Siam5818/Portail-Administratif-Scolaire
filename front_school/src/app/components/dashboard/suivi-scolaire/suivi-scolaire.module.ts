import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { BulletinsPageComponent } from './bulletins-page/bulletins-page.component';
import { NotesPageComponent } from './notes-page/notes-page.component';
import { SuiviScolaireComponent } from './suivi-scolaire.component';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { RouterModule } from '@angular/router';
import { BulletinsGenerationComponent } from './bulletins-page/bulletinsgeneration.component';
import { NotesFormComponent } from './notes-page/notefrom.component';

@NgModule({
  declarations: [
    SuiviScolaireComponent,
    NotesPageComponent,
    BulletinsPageComponent,
    BulletinsGenerationComponent,
    NotesFormComponent,
  ],
  imports: [CommonModule, RouterModule, ReactiveFormsModule, FormsModule],
})
export class SuiviScolaireModule {}
