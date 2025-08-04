import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { catchError, throwError } from 'rxjs';
import { AuthService } from './auth.service';
import { Tuteur } from '../models/tuteur';
import { UpdateTuteurResponse } from '../models/update-tuteur-response';

@Injectable({
  providedIn: 'root',
})
export class TuteurService {
  private api_Url = 'http://127.0.0.1:8000/api/v1/tuteurs';

  constructor(
    private httpclient: HttpClient,
    private authService: AuthService
  ) {}

  private searchUrl = `${this.api_Url}/search`;

  private handleError(error: any) {
    console.error('Erreur API:', error);
    return throwError(() => error);
  }

  private getHeaders(): HttpHeaders {
    const token = this.authService.getToken();
    if (token) {
      return new HttpHeaders({
        Authorization: 'Bearer ' + token,
      });
    }
    return new HttpHeaders();
  }

  getTuteurs() {
    return this.httpclient
      .get<Tuteur[]>(this.api_Url, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  getTuteurById(id: number) {
    return this.httpclient
      .get<Tuteur>(this.api_Url + '/' + id, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  updateTuteur(id: number, data: any) {
    return this.httpclient
      .put<UpdateTuteurResponse>(this.api_Url + '/' + id, data, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  search(motCle: string) {
    return this.httpclient
      .get<Tuteur[]>(this.searchUrl, {
        headers: this.getHeaders(),
        params: { motcle: motCle },
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
