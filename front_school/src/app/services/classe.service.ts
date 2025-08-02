import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Classe } from '../models/classe';
import { AuthService } from './auth.service';
import { catchError, throwError } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class ClasseService {
  private api_Url = 'http://127.0.0.1:8000/api/v1/classes';

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
    return new HttpHeaders({
      Authorization: 'Bearer ' + this.authservice.getToken(),
    });
  }

  getClasses() {
    return this.httpclient
      .get<Classe[]>(this.api_Url, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  getClasseById(id: number) {
    return this.httpclient
      .get<Classe>(this.api_Url + '/' + id, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  addClasse(classe: Classe) {
    return this.httpclient
      .post<Classe>(this.api_Url, classe, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  updateClasse(classe: Classe) {
    return this.httpclient
      .put<Classe>(this.api_Url + '/' + classe.id, classe, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  deleteClasse(id: number) {
    return this.httpclient
      .delete(this.api_Url + '/' + id, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  search(motcle: string) {
    const params = new HttpParams().set('query', motcle);
    return this.httpclient
      .get<Classe[]>(this.searchUrl, {
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
