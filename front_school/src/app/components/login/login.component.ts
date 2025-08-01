import { Component } from '@angular/core';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../../services/auth.service';
import { Login } from '../../models/login';
import { TokenResponse } from '../../models/token';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
})
export class LoginComponent {
  loginForm: FormGroup;
  submittedForm = false;
  successMessage = '';
  errorMessage = '';
  isLoading = false;

  constructor(private authService: AuthService, private router: Router) {
    this.loginForm = new FormGroup({
      email: new FormControl('', [Validators.required, Validators.email]),
      password: new FormControl('', [Validators.required]),
    });
  }

  get tbErreurFront() {
    return this.loginForm.controls;
  }

  onLogin(): void {
    this.submittedForm = true;
    this.successMessage = '';
    this.errorMessage = '';

    if (this.loginForm.invalid) return;

    const connecter = this.loginForm.value as Login;
    this.isLoading = true;

    this.authService.login(connecter).subscribe({
      next: (res: TokenResponse) => this.handleLoginSuccess(res),
      error: (err) => this.handleLoginError(err),
      complete: () => (this.isLoading = false),
    });
  }

  private handleLoginSuccess(res: TokenResponse): void {
    this.successMessage = 'Connexion réussie';
    this.authService.saveToken(res.access_token);
    this.authService.saveUser(res.user);
    this.loginForm.reset();

    if (res.user.must_change_password) {
      this.router.navigateByUrl('/change-password');
      return;
    }

    const redirectUrl = this.authService.getRedirectUrlByRole(res.user.role);
    if (redirectUrl) {
      this.router.navigateByUrl(redirectUrl);
    } else {
      this.errorMessage = 'Rôle inconnu, accès refusé';
    }
  }

  private handleLoginError(err: any): void {
    this.errorMessage =
      err?.error?.message ||
      'Identifiants incorrects ou utilisateur introuvable';
    this.isLoading = false;
  }
}
