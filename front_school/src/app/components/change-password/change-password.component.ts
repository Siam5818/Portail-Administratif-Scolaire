import { Component } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { AuthService } from '../../services/auth.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-change-password',
  templateUrl: './change-password.component.html',
  styleUrls: ['./change-password.component.css'],
})
export class ChangePasswordComponent {
  passwordForm: FormGroup;
  successMessage = '';
  errorMessage = '';
  isLoading = false;

  constructor(private authService: AuthService, private router: Router) {
    this.passwordForm = new FormGroup({
      new_password: new FormControl('', [
        Validators.required,
        Validators.minLength(8),
      ]),
    });
  }

  get tbErreurFront() {
    return this.passwordForm.controls;
  }

  onChangePassword(): void {
    this.successMessage = '';
    this.errorMessage = '';
    this.isLoading = true;

    if (this.passwordForm.invalid) return;

    const newPassword = this.passwordForm.value.new_password;

    this.authService.changePassword({ new_password: newPassword }).subscribe({
      next: () => this.handleChangePasswordSuccess(),
      error: (err) => this.handleChangePasswordError(err),
      complete: () => (this.isLoading = false),
    });
  }

  handleChangePasswordSuccess(): void {
    this.successMessage = 'Mot de passe changé avec succès.';
    this.passwordForm.reset();
    const user = this.authService.getCurrentUser();
    const redirectUrl = this.authService.getRedirectUrlByRole(user?.role);

    if (redirectUrl) {
      this.router.navigateByUrl(redirectUrl);
    } else {
      this.errorMessage = 'Rôle inconnu, redirection impossible.';
    }
    
  }

  handleChangePasswordError(err: any): void {
    this.errorMessage = err.error?.message || 'Une erreur est survenue.';
  }
}
