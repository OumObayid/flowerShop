import { CommonModule } from '@angular/common';
import { Component, OnInit} from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { Store } from '@ngrx/store';
import { LoginService } from '../../../services/auth/login.service';
import { getActiveUserInfo, setActiveUser } from '../../../ngrx/data.slice';


@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, FormsModule,RouterLink],
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {

  email: string = '';
  password: string = '';
  errorMessage: string | null = null;

  constructor(
    private loginService: LoginService,
    public router: Router,
    private store: Store
  ) {}

  ngOnInit(): void {
    const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
    
    if (isLoggedIn) {
      this.router.navigate(['/dashboard']); // Rediriger l'utilisateur vers dashboard
    }
  }

  // onLogin(){
  //   console.log(this.email, this.password);
  //   this.loginService.login(this.email, this.password).subscribe({
  //     next:(response) => {
  //       if (response.message === 1)
  //       {this.store.dispatch(setActiveUser());
  //       this.router.navigate(['/dashboard']);}
  //       else {
  //         this.errorMessage = response; // Affichage des erreurs renvoyées par le serveur
  //       }
  //     },
  //     error: () => {
  //       this.errorMessage = 'Erreur de connexion au serveur';
  //     },
      
  //   });
  // }
  onLogin() {
    this.loginService.login(this.email, this.password).subscribe({
      next: (response) => {
        if (response.success  && response.user) {
          this.store.dispatch(setActiveUser());
          this.store.dispatch(getActiveUserInfo(response.user));
          localStorage.setItem('user', JSON.stringify(response.user));
          this.router.navigate(['/dashboard']);
        } else {
          this.errorMessage = response.error || 'Erreur d\'authentification';
        }
      },
      error: () => {
        this.errorMessage = 'Erreur de connexion au serveur';
      },
    });
  }
}
