import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ActivityFeedComponent } from './activity-feed/activity-feed.component';
import { NotificationListComponent } from './notification-list/notification-list.component';
import { StatChartComponent } from './stat-chart/stat-chart.component';
import { DashboardComponent } from './dashboard.component';
import { UtilisateurComponent } from './utilisateur/utilisateur.component';
import { ClassesComponent } from './classes/classes.component';
import { MatieresComponent } from './matieres/matieres.component';
import { NotesComponent } from './notes/notes.component';
import { BulletinsComponent } from './bulletins/bulletins.component';
import { CardStatComponent } from './card-stat/card-stat.component';

@NgModule({
  declarations: [
    DashboardComponent,
    NotificationListComponent,
    ActivityFeedComponent,
    StatChartComponent,
    UtilisateurComponent,
    ClassesComponent,
    MatieresComponent,
    NotesComponent,
    BulletinsComponent,
    CardStatComponent,
  ],
  imports: [CommonModule],
})
export class DashboardModule {}
