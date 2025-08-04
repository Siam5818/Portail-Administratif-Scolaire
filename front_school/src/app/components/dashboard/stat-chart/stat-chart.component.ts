import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ChartConfiguration, ChartType } from 'chart.js';
import { ClasseService } from '../../../services/classe.service';
import { EleveService } from '../../../services/eleve.service';
import { EnseignantService } from '../../../services/enseignant.service';
import { MatiereService } from '../../../services/matiere.service';
import { TuteurService } from '../../../services/tuteur.service';

@Component({
  selector: 'app-stat-chart',
  templateUrl: './stat-chart.component.html',
  styleUrls: ['./stat-chart.component.css'],
})
export class StatChartComponent implements OnInit {
  public chartData: ChartConfiguration<'bar'>['data'] = {
    labels: ['Classes', 'Élèves', 'Enseignants', 'Tuteurs', 'Matières'],
    datasets: [
      {
        data: [],
        label: 'Répartition',
        backgroundColor: [
          '#42A5F5',
          '#66BB6A',
          '#FFA726',
          '#AB47BC',
          '#EF5350',
        ],
      },
    ],
  };

  public chartType: ChartType = 'bar';

  constructor(
    private http: HttpClient,
    private classeService: ClasseService,
    private eleveService: EleveService,
    private enseignantService: EnseignantService,
    private tuteurService: TuteurService,
    private matiereService: MatiereService
  ) {}

  ngOnInit(): void {
    Promise.all([
      this.classeService.count().toPromise(),
      this.eleveService.count().toPromise(),
      this.enseignantService.count().toPromise(),
      this.tuteurService.count().toPromise(),
      this.matiereService.count().toPromise(),
    ]).then((results) => {
      this.chartData.datasets[0].data = results.map((r) => r.total);
    });
  }
}
