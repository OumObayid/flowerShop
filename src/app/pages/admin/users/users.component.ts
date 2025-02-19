import { Store } from '@ngrx/store';
import { User } from './../../../interfaces/user';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-users',
  imports: [],
  templateUrl: './users.component.html',
  styleUrl: './users.component.css'
})
export class UsersComponent implements OnInit{

   user:User | null=null;

   constructor(public store:Store){}

   ngOnInit(): void {
     
   }
}
