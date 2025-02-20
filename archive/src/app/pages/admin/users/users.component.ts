import { UsersService } from './../../../services/users/users.service';
import { Store } from '@ngrx/store';
import { User } from './../../../interfaces/user';
import { Component, OnInit } from '@angular/core';
import { selectUsers } from '../../../ngrx/data.slice';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-users',
  imports: [CommonModule, FormsModule],
  templateUrl: './users.component.html',
  styleUrl: './users.component.css',
})
export class UsersComponent implements OnInit {
  users: User[] | null = null;

  constructor(public store: Store) {}

  ngOnInit(): void {
    this.store.select(selectUsers).subscribe((datauser) => {
      console.log('datauser :', datauser);
      this.users = datauser;
    });
  }
}
