import { Component } from '@angular/core';
import { AuthService } from '../../services/auth.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.css'],
})
export class NavbarComponent {
  role: string = '';
  isMenuOpen: boolean = false;

  constructor(private authservice: AuthService, private router: Router) {
    // Vérification du rôle de l'utilisateur à la création du composant
    const user = this.authservice.getCurrentUser();
    if (user) {
      this.role = user.role;
    }
  }

  // Méthode pour basculer l'état du menu
  toggleMenu() {
    this.isMenuOpen = !this.isMenuOpen;
  }

  // Méthode pour gérer la déconnexion
  logout() {
    this.authservice.logout().subscribe({
      next: () => {
        this.authservice.clearToken();
        this.authservice.clearUser();
        this.router.navigateByUrl('/login');
      },
      error: (err) => {
        console.error('Erreur lors de la déconnexion', err);
      },
    });
    // Logique de déconnexion ici, par exemple, effacer le token d'authentification
    console.log('Déconnexion réussie');
  }
}
