import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-activite-recente',
  templateUrl: './activite-recente.component.html',
  styleUrl: './activite-recente.component.css'
})
export class ActiviteRecenteComponent {
  @Input() data: any[] = [];
}
