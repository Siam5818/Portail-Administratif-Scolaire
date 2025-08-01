import { CanActivateFn, Router } from '@angular/router';
import { inject } from '@angular/core';
import { AuthService } from '../services/auth.service';

export const authGuard: CanActivateFn = (route, state) => {
  const router = inject(Router);
  const authService = inject(AuthService);

  const user = authService.getCurrentUser();

  if (!user) {
    return router.parseUrl('/login');
  }

  const allowedRoles = route.data?.['role'] as string[] | undefined;
  // Vérifier si l'utilisateur a un rôle autorisé
  if (allowedRoles && !allowedRoles.includes(user.role)) {
    console.log(`Accès refusé pour le rôle : ${user.role}`);
    return router.parseUrl('/login');
  }

  // Si l'utilisateur est authentifié et a un rôle autorisé, autoriser l'accès
  if (user) {
    return true;
  }
  // Si l'utilisateur n'est pas authentifié, rediriger vers la page de connexion
  console.log(`Accès autorisé pour le rôle : ${user.role}`);
  return true;
};
