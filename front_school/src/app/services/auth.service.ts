import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Login } from '../models/login';
import { TokenResponse } from '../models/token';
import { User } from '../models/user';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  private api_Url = 'http://127.0.0.1:8000/api/v1';

  constructor(private httpclient: HttpClient) {}

  getAuthorizationHeaders(): HttpHeaders {
    const token = this.getToken();
    return new HttpHeaders({
      Authorization: `Bearer ${token}`,
    });
  }

  login(data: Login) {
    return this.httpclient.post<TokenResponse>(`${this.api_Url}/login`, data);
  }

  changePassword(data: { new_password: string }) {
    return this.httpclient.post<{ message: string }>(
      `${this.api_Url}/change-password`,
      data,
      { headers: this.getAuthorizationHeaders() }
    );
  }

  logout() {
    return this.httpclient.post<TokenResponse>(
      `${this.api_Url}/logout`,
      {},
      { headers: this.getAuthorizationHeaders() }
    );
  }

  saveToken(token: string) {
    localStorage.setItem('token', token);
  }

  getToken() {
    return localStorage.getItem('token');
  }

  isLoggedIn(): boolean {
    return !!this.getToken();
  }

  clearToken() {
    localStorage.removeItem('token');
  }

  saveUser(user: User): void {
    localStorage.setItem('user', JSON.stringify(user));
  }

  clearUser(): void {
    localStorage.removeItem('user');
  }

  getCurrentUser(): any {
    if (typeof window !== 'undefined') {
      const user = localStorage.getItem('user');
      console.log('Utilisateur récupéré :', user);
      return user ? JSON.parse(user) : null;
    }
    return null;
  }

  getRedirectUrlByRole(role: string): string | null {
    switch (role) {
      case 'admin':
        return '/dashboard';
      case 'enseignant':
        return '/espce-ensignant';
      case 'tuteur':
      case 'eleve':
        return '/Accueil';
      default:
        return null;
    }
  }
}
