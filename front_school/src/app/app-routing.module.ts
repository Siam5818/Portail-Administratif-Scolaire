import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from './components/login/login.component';
import { DashboardComponent } from './components/dashboard/dashboard.component';
import { PageNotFoundComponent } from './components/page-not-found/page-not-found.component';
import { ChangePasswordComponent } from './components/change-password/change-password.component';
import { authGuard } from './guards/auth.guard';
import { DashprofComponent } from './components/dashprof/dashprof.component';
import { DashfamilleComponent } from './components/dashfamille/dashfamille.component';

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
