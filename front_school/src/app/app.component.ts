import { Component } from '@angular/core';
import { NavigationEnd, Router } from '@angular/router';
import { filter } from 'rxjs/operators';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrl: './app.component.css',
})
export class AppComponent {
  // Titre de l'application
  title = 'front_school';

  // Variable pour contrôler l'affichage du layout principal
  afficheLayout = true;

  constructor(private router: Router) {
    // On écoute les événements de navigation du routeur Angular
    // On filtre uniquement les événements de type NavigationEnd (fin de navigation)
    // On s'abonne à ces événements filtrés
    this.router.events
      .pipe(
        filter(
          (event): event is NavigationEnd => event instanceof NavigationEnd
        )
      )
      .subscribe((event: NavigationEnd) => {
        // Liste des routes où le layout ne doit pas s'afficher
        const hiddenRoutes = ['/login', '/**', '/change-password'];

        // Si la route actuelle est dans hiddenRoutes, on cache le layout
        // Sinon, on l'affiche
        this.afficheLayout = !hiddenRoutes.includes(event.urlAfterRedirects);

        // Log dans la console pour indiquer que la navigation est terminée
        console.log('Navigation terminée');
      });
  }
}
