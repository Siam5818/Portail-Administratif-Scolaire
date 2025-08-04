import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { catchError, throwError } from 'rxjs';
import { AuthService } from './auth.service';
import { Note } from '../models/note';

@Injectable({
  providedIn: 'root',
})
export class NoteService {
  private api_Url = 'http://127.0.0.1:8000/api/v1/notes';

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

  getNotes() {
    return this.httpclient
      .get<Note[]>(this.api_Url, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  getNoteById(id: number) {
    return this.httpclient
      .get<Note>(this.api_Url + '/' + id, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  addNote(note: Note) {
    return this.httpclient
      .post<Note>(this.api_Url, note, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  updateNote(note: Note) {
    return this.httpclient
      .put<Note>(this.api_Url + '/' + note.id, note, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  deleteNote(id: number) {
    return this.httpclient
      .delete(this.api_Url + '/' + id, {
        headers: this.getHeaders(),
      })
      .pipe(catchError(this.handleError));
  }

  search(filters: {
    eleve_id?: number;
    matiere_id?: number;
    periode?: string;
  }) {
    const params = new HttpParams({ fromObject: filters });

    return this.httpclient
      .get<Note[]>(this.searchUrl, {
        headers: this.getHeaders(),
        params,
      })
      .pipe(catchError(this.handleError));
  }

  getNotesByEleveId(eleveId: number) {
    return this.search({ eleve_id: eleveId });
  }
}
