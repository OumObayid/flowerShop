import { CanActivateFn } from '@angular/router';

export const authGuard: CanActivateFn = (route, state) => {
  const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';

  if (!isLoggedIn) {
    window.location.href = '/login'; // Rediriger l'utilisateur non connecté vers la page de connexion
    return false;
  }

  return true;
};
