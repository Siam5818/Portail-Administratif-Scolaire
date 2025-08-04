import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { EleveComponent } from './eleve/eleve.component';
import { EnseignantComponent } from './enseignant/enseignant.component';
import { UtilisateurComponent } from './utilisateur.component';
import { EleveFormComponent } from './eleve/eleveform.component';
import { RouterModule } from '@angular/router';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

@NgModule({
  declarations: [
    UtilisateurComponent,
    EnseignantComponent,
    EleveComponent,
    EleveFormComponent,
  ],
  imports: [CommonModule, RouterModule, ReactiveFormsModule, FormsModule],
  exports: [UtilisateurComponent],
})
export class UtilisateurModule {}
