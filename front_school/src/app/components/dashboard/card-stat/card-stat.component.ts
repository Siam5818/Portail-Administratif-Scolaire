import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-card-stat',
  templateUrl: './card-stat.component.html',
  styleUrl: './card-stat.component.css',
})
export class CardStatComponent {
  @Input() title: string = '';
  @Input() value: string | number = '';
  @Input() icon: string = '';
  @Input() color: string = 'gray';
}
