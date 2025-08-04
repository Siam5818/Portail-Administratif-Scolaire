import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { StatChartComponent } from './stat-chart/stat-chart.component';
import { DashboardComponent } from './dashboard.component';
import { ClassesComponent } from './classes/classes.component';
import { MatieresComponent } from './matieres/matieres.component';
import { NotesComponent } from './notes/notes.component';
import { CardStatComponent } from './card-stat/card-stat.component';
import { NgChartsModule } from 'ng2-charts';
import { UtilisateurModule } from './utilisateur/utilisateur.module';
import { SuiviScolaireModule } from './suivi-scolaire/suivi-scolaire.module';
import { UserComponent } from './user/user.component';
import { MatiereFormComponent } from './matieres/matiereform.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { RouterModule } from '@angular/router';
import { ClasseFormComponent } from './classes/classeform.component';
import { ActiviteRecenteComponent } from './activite-recente/activite-recente.component';

@NgModule({
  declarations: [
    DashboardComponent,
    StatChartComponent,
    ClassesComponent,
    MatieresComponent,
    MatiereFormComponent,
    NotesComponent,
    CardStatComponent,
    UserComponent,
    ClasseFormComponent,
    ActiviteRecenteComponent,
  ],
  imports: [
    NgChartsModule,
    CommonModule,
    UtilisateurModule,
    SuiviScolaireModule,
    UtilisateurModule,
    RouterModule,
    FormsModule,
    ReactiveFormsModule,
  ],
})
export class DashboardModule {}
