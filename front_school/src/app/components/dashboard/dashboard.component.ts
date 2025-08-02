import { Component } from '@angular/core';
import { AuthService } from '../../services/auth.service';
import { EleveService } from '../../services/eleve.service';
import { EnseignantService } from '../../services/enseignant.service';
import { MatiereService } from '../../services/matiere.service';
import { TuteurService } from '../../services/tuteur.service';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css'],
})
export class DashboardComponent {
  greeting: string = '';
  adminName: string = '';
  stats = {
    eleves: 0,
    enseignants: 0,
    tuteurs: 0,
    cours: 0,
  };

  constructor(
    private authService: AuthService,
    private eleveService: EleveService,
    private enseignantService: EnseignantService,
    private tuteurService: TuteurService,
    private matiereService: MatiereService
  ) {}

  ngOnInit(): void {
    this.setAdminName();
    this.setGreeting();
    this.loadStats();
  }

  setAdminName(): void {
    const user = this.authService.getCurrentUser();
    this.adminName = user?.nom + ' ' + user?.prenom || 'Administrateur';
  }

  setGreeting(): void {
    const hour = new Date().getHours();
    if (hour < 12) {
      this.greeting = 'Bonjour';
    } else if (hour < 18) {
      this.greeting = 'Bon après-midi';
    } else {
      this.greeting = 'Bonsoir';
    }
  }

  loadStats() {
    this.eleveService
      .count()
      .subscribe((res) => (this.stats.eleves = res.total));
    this.enseignantService
      .count()
      .subscribe((res) => (this.stats.enseignants = res.total));
    this.tuteurService
      .count()
      .subscribe((res) => (this.stats.tuteurs = res.total));
    this.matiereService
      .count()
      .subscribe((res) => (this.stats.cours = res.total));
  }
}
