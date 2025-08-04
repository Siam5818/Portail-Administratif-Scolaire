import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { EleveComponent } from './eleve/eleve.component';
import { EnseignantComponent } from './enseignant/enseignant.component';
import { UtilisateurComponent } from './utilisateur.component';
import { EleveFormComponent } from './eleve/eleveform.component';
import { RouterModule } from '@angular/router';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { EnseignantFormComponent } from './enseignant/enseignantform.component';

@NgModule({
  declarations: [
    UtilisateurComponent,
    EnseignantComponent,
    EleveComponent,
    EleveFormComponent,
    EnseignantFormComponent,
  ],
  imports: [CommonModule, RouterModule, ReactiveFormsModule, FormsModule],
  exports: [UtilisateurComponent],
})
export class UtilisateurModule {}
