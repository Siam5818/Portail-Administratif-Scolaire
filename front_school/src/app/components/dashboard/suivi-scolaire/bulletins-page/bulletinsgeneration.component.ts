import { Component, OnInit } from '@angular/core';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { EleveResponse } from '../../../../models/eleve-response';
import { BulletinDetail } from '../../../../models/bulletin-detail';
import { BulletinService } from '../../../../services/bulletin.service';
import { EleveService } from '../../../../services/eleve.service';
import { AuthService } from '../../../../services/auth.service';

@Component({
  selector: 'app-bulletinsgeneration',
  templateUrl: './bulletinsgeneration.component.html',
  styleUrls: ['./bulletins-page.component.css'],
})
export class BulletinsGenerationComponent implements OnInit {
  eleve_id!: number;
  eleve!: EleveResponse;
  bulletinForm!: FormGroup;
  generatedBulletin?: BulletinDetail;
  errorMessage: string = '';
  successMessage: string = '';
  isLoading: boolean = false;
  currentYear: number = new Date().getFullYear();

  constructor(
    private route: ActivatedRoute,
    private eleveService: EleveService,
    private bulletinService: BulletinService,
    private router: Router,
    private authService: AuthService
  ) {}

  ngOnInit(): void {
    if (!this.authService.isLoggedIn()) {
      this.router.navigate(['/login']);
      return;
    }

    this.route.params.subscribe((params) => {
      this.eleve_id = +params['id'];

      this.initForm();
      this.loadEleve();
    });
  }

  initForm(): void {
    this.bulletinForm = new FormGroup({
      periode: new FormControl('', Validators.required),
      annee: new FormControl(new Date().getFullYear(), [
        Validators.required,
        Validators.min(2000),
        Validators.max(new Date().getFullYear()),
      ]),
    });
  }

  loadEleve(): void {
    this.eleveService.getEleveById(this.eleve_id).subscribe({
      next: (data) => {
        this.eleve = data;
        console.log("Donnee du bulletin: ",  this.eleve);
      },
      error: () => (this.errorMessage = "Impossible de charger l'élève."),
    });
  }

  createBulletin(): void {
    if (this.bulletinForm.invalid || !this.eleve_id) {
      this.errorMessage = 'Veuillez remplir tous les champs correctement.';
      return;
    }

    this.isLoading = true;
    const payload = {
      eleve_id: this.eleve_id,
      ...this.bulletinForm.value,
    };

    console.log('Payload envoyé au backend:', payload);

    this.bulletinService.createBulletin(payload).subscribe({
      next: () => {
        this.successMessage = 'Bulletin créé avec succès.';
        this.errorMessage = '';
        this.bulletinForm.reset({ annee: new Date().getFullYear() });

        this.bulletinService.searchBulletins(payload).subscribe({
          next: (bulletins) => {
            this.generatedBulletin = bulletins[0];
            this.isLoading = false;
          },
          error: () => {
            this.errorMessage = 'Bulletin créé, mais impossible de le charger.';
            this.isLoading = false;
          },
        });
      },
      error: () => {
        this.errorMessage = 'Erreur lors de la création du bulletin.';
        this.successMessage = '';
        this.isLoading = false;
      },
    });
  }

  getAppreciationClass(appreciation: string): string {
    switch (appreciation) {
      case 'Excellent':
        return 'text-success fw-bold';
      case 'Très Bien':
        return 'text-primary';
      case 'Insuffisant':
        return 'text-danger fw-bold';
      default:
        return '';
    }
  }
}
