import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { AuthService } from './auth.service';
import { catchError, throwError } from 'rxjs';
import { Enseignant } from '../models/enseignant';

@Injectable({
  providedIn: 'root',
})
export class EnseignantService {
  private api_Url = 'http://127.0.0.1:8000/api/v1/enseignants';

  constructor(
    private httpclient: HttpClient,
    private authservice: AuthService
  ) {}

  private searchUrl = `${this.api_Url}/search`;

  private getHeaders(): HttpHeaders {
    return new HttpHeaders({
      Authorization: 'Bearer ' + this.authservice.getToken(),
    });
  }

  private handleError(error: any) {
    console.error('Erreur API:', error);
    return throwError(() => error);
  }

  getEnseignants() {
    return this.httpclient
      .get<Enseignant[]>(this.api_Url, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  getEnseignantById(id: number) {
    return this.httpclient
      .get<Enseignant>(this.api_Url + '/' + id, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  addEnseignant(enseignant: Enseignant) {
    return this.httpclient
      .post<Enseignant>(this.api_Url, enseignant, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  updateEnseignant(enseignant: Enseignant) {
    return this.httpclient
      .put<Enseignant>(this.api_Url + '/' + enseignant.id, enseignant, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  deleteEnseignant(id: number) {
    return this.httpclient
      .delete(this.api_Url + '/' + id, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  search(motcle: string) {
    const params = { motcle: motcle };
    return this.httpclient
      .get<Enseignant[]>(this.searchUrl, {
        headers: this.getHeaders(),
        params: params,
      })
      .pipe(catchError(this.handleError));
  }
}
