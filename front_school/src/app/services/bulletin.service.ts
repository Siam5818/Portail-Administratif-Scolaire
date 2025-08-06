import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { throwError } from 'rxjs';
import { AuthService } from './auth.service';
import { BulletinDetail } from '../models/bulletin-detail';

@Injectable({
  providedIn: 'root',
})
export class BulletinService {
  private api_Url = 'http://127.0.0.1:8000/api/v1/bulletins';

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

  getBulletins() {
    return this.httpclient.get<BulletinDetail[]>(this.api_Url, {
      headers: this.getHeaders(),
    });
  }

  getBulletinById(id: number) {
    return this.httpclient.get<BulletinDetail>(`${this.api_Url}/${id}`, {
      headers: this.getHeaders(),
    });
  }

  createBulletin(payload: {
    eleve_id: number;
    periode: string;
    annee: number;
  }) {
    return this.httpclient.post<any>(this.api_Url, payload, {
      headers: this.getHeaders(),
    });
  }

  updateBulletin(id: number, payload: { periode?: string; annee?: number }) {
    return this.httpclient.put(`${this.api_Url}/${id}`, payload, {
      headers: this.getHeaders(),
    });
  }

  deleteBulletin(id: number) {
    return this.httpclient.delete(`${this.api_Url}/${id}`, {
      headers: this.getHeaders(),
    });
  }

  searchBulletins(filters: {
    eleve_id?: number;
    periode?: string;
    annee?: number;
  }) {
    const params = new HttpParams({ fromObject: filters });
    return this.httpclient.get<BulletinDetail[]>(this.searchUrl, {
      params,
      headers: this.getHeaders(),
    });
  }
}
