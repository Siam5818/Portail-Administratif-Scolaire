import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { AuthService } from './auth.service';
import { catchError, throwError } from 'rxjs';
import { Matiere } from '../models/matiere';

@Injectable({
  providedIn: 'root',
})
export class MatiereService {
  private api_Url = 'http://127.0.0.1:8000/api/v1/matieres';

  constructor(
    private httpclient: HttpClient,
    private authservice: AuthService
  ) {}

  private searchUrl = `${this.api_Url}/search`;

  private handleError(error: any) {
    console.error('Erreur API:', error);
    return throwError(() => error);
  }

  private getHeaders(): HttpHeaders {
    const token = this.authservice.getToken();
    if (token) {
      return new HttpHeaders({
        Authorization: 'Bearer ' + token,
      });
    }
    return new HttpHeaders();
  }

  getMatieres() {
    return this.httpclient
      .get<Matiere[]>(this.api_Url, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  getMatiereById(id: number) {
    return this.httpclient
      .get<Matiere>(this.api_Url + '/' + id, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  addMatiere(matiere: Matiere) {
    return this.httpclient
      .post<Matiere>(this.api_Url, matiere, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  updateMatiere(matiere: Matiere) {
    return this.httpclient
      .put<Matiere>(this.api_Url + '/' + matiere.id, matiere, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  deleteMatiere(id: number) {
    return this.httpclient
      .delete(this.api_Url + '/' + id, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  search(motcle: string) {
    const params = new HttpParams().set('query', motcle);
    return this.httpclient
      .get<Matiere[]>(this.searchUrl, {
        headers: this.getHeaders(),
        params: params,
      })
      .pipe(catchError(this.handleError));
  }

  count() {
    return this.httpclient
      .get<{ total: number }>(`${this.api_Url}/count`, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }
}
