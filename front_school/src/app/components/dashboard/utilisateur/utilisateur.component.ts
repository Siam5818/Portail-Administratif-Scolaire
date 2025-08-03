import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-utilisateur',
  templateUrl: './utilisateur.component.html',
  styleUrl: './utilisateur.component.css',
})
export class UtilisateurComponent {
  @Input() data: any[] = [];
}
