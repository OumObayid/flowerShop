import { CommonModule } from '@angular/common';
import { Component, OnInit, signal } from '@angular/core';
import {  Router, RouterLink } from '@angular/router';
import { Store } from '@ngrx/store';
import { removeActiveUser, selectIsLoggedIn } from '../../ngrx/data.slice';

@Component({
  selector: 'app-header',
  imports: [CommonModule,RouterLink],
  templateUrl: './header.component.html',
  styleUrl: './header.component.css'
})
export class HeaderComponent implements OnInit {
  
  constructor(public router: Router,
    private store: Store) {  }
    isloggedIn = signal(false);
 
    ngOnInit() {
      this.store
        .select(selectIsLoggedIn)
        .subscribe((islog) => this.isloggedIn.set(islog));
    }
  
  logout(){
    this.store.dispatch(removeActiveUser());
    this.router.navigate(['/login']);
  }
}
