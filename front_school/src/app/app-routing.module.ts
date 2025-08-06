import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from './components/login/login.component';
import { DashboardComponent } from './components/dashboard/dashboard.component';
import { PageNotFoundComponent } from './components/page-not-found/page-not-found.component';
import { ChangePasswordComponent } from './components/change-password/change-password.component';
import { authGuard } from './guards/auth.guard';
import { DashprofComponent } from './components/dashprof/dashprof.component';
import { DashfamilleComponent } from './components/dashfamille/dashfamille.component';
import { EleveComponent } from './components/dashboard/utilisateur/eleve/eleve.component';
import { EnseignantComponent } from './components/dashboard/utilisateur/enseignant/enseignant.component';
import { ClassesComponent } from './components/dashboard/classes/classes.component';
import { MatieresComponent } from './components/dashboard/matieres/matieres.component';
import { BulletinsPageComponent } from './components/dashboard/suivi-scolaire/bulletins-page/bulletins-page.component';
import { NotesPageComponent } from './components/dashboard/suivi-scolaire/notes-page/notes-page.component';
import { EleveFormComponent } from './components/dashboard/utilisateur/eleve/eleveform.component';
import { EnseignantFormComponent } from './components/dashboard/utilisateur/enseignant/enseignantform.component';
import { MatiereFormComponent } from './components/dashboard/matieres/matiereform.component';
import { ClasseFormComponent } from './components/dashboard/classes/classeform.component';
import { NotesFormComponent } from './components/dashboard/suivi-scolaire/notes-page/notefrom.component';
import { BulletinsGenerationComponent } from './components/dashboard/suivi-scolaire/bulletins-page/bulletinsgeneration.component';

const routes: Routes = [
  { path: '', redirectTo: '/login', pathMatch: 'full' },

  { path: 'login', component: LoginComponent },

  {
    path: 'change-password',
    component: ChangePasswordComponent,
    canActivate: [authGuard],
  },
  // Espace administrateur
  {
    path: 'dashboard',
    component: DashboardComponent,
    canActivate: [authGuard],
  },

  {
    path: 'gestion-utilisateurs/eleves',
    component: EleveComponent,
    canActivate: [authGuard],
  },
  {
    path: 'gestion-utilisateurs/ajouter-eleve',
    component: EleveFormComponent,
    canActivate: [authGuard],
  },
  {
    path: 'gestion-utilisateurs/modifier-eleve/:id',
    component: EleveFormComponent,
    canActivate: [authGuard],
  },
  //-------
  {
    path: 'gestion-utilisateurs/enseignants',
    component: EnseignantComponent,
    canActivate: [authGuard],
  },
  {
    path: 'gestion-utilisateurs/ajouter-enseignant',
    component: EnseignantFormComponent,
    canActivate: [authGuard],
  },
  {
    path: 'gestion-utilisateurs/modifier-enseignant/:id',
    component: EnseignantFormComponent,
    canActivate: [authGuard],
  },
  //---------------
  {
    path: 'gestion-classes',
    component: ClassesComponent,
    canActivate: [authGuard],
  },
  {
    path: 'ajouter-classe',
    component: ClasseFormComponent,
    canActivate: [authGuard],
  },
  {
    path: 'modifier-classe/:id',
    component: ClasseFormComponent,
    canActivate: [authGuard],
  },

  {
    path: 'gestion-matieres',
    component: MatieresComponent,
    canActivate: [authGuard],
  },
  {
    path: 'ajouter-matiere',
    component: MatiereFormComponent,
    canActivate: [authGuard],
  },
  {
    path: 'modifier-matiere/:id',
    component: MatiereFormComponent,
    canActivate: [authGuard],
  },
  //---------------
  {
    path: 'suivi-scolaire/notes',
    component: NotesPageComponent,
    canActivate: [authGuard],
  },
  {
    path: 'suivi-scolaire/ajouter-note',
    component: NotesFormComponent,
    canActivate: [authGuard],
  },
  {
    path: 'suivi-scolaire/modifier-note/:id',
    component: NotesFormComponent,
    canActivate: [authGuard],
  },
  {
    path: 'suivi-scolaire/bulletins',
    component: BulletinsPageComponent,
    canActivate: [authGuard],
  },
  {
    path: 'suivi-scolaire/bulletins/generer/:id',
    component: BulletinsGenerationComponent,
    canActivate: [authGuard],
  },
  // Espace enseignant
  {
    path: 'espace-enseignant',
    component: DashprofComponent,
    canActivate: [authGuard],
  },
  // Espace famille
  {
    path: 'espace-famille',
    component: DashfamilleComponent,
    canActivate: [authGuard],
  },

  // Redirection pour les routes non définies
  { path: '**', component: PageNotFoundComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule],
})
export class AppRoutingModule {}
