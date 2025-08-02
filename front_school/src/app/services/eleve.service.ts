import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { catchError, throwError } from 'rxjs';
import { AuthService } from './auth.service';
import { Eleve } from '../models/eleve';
import { EleveFormPayload } from '../models/eleve-form-payload';

@Injectable({
  providedIn: 'root',
})
export class EleveService {
  private api_Url = 'http://127.0.0.1:8000/api/v1/eleves';

  constructor(
    private httpclient: HttpClient,
    private authservice: AuthService
  ) {}

  private searchUrl = `${this.api_Url}/search`;
  private countUrl = `${this.api_Url}/count`;

  private getHeaders(): HttpHeaders {
    return new HttpHeaders({
      Authorization: 'Bearer ' + this.authservice.getToken(),
    });
  }

  private handleError(error: any) {
    console.error('Erreur API:', error);
    return throwError(() => error);
  }

  getEleves() {
    return this.httpclient
      .get<Eleve[]>(this.api_Url, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  getEleveById(id: number) {
    return this.httpclient
      .get<Eleve>(this.api_Url + '/' + id, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  addEleve(elevefrompayload: EleveFormPayload) {
    return this.httpclient
      .post<EleveFormPayload>(this.api_Url, elevefrompayload, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  search(motcle: string) {
    const params = { motcle: motcle };
    return this.httpclient
      .get<Eleve[]>(this.searchUrl, {
        headers: this.getHeaders(),
        params: params,
      })
      .pipe(catchError(this.handleError));
  }

  count() {
    return this.httpclient
      .get<any>(this.countUrl, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }
}
