import { Component, OnInit } from '@angular/core';
import { Store } from '@ngrx/store';
import { Categorie } from '../../../interfaces/categorie';

@Component({
  selector: 'app-categories',
  imports: [],
  templateUrl: './categories.component.html',
  styleUrl: './categories.component.css'
})
export class CategoriesComponent implements OnInit {

  categories:Categorie | null=null;
  
     constructor(public store:Store){}
  
     ngOnInit(): void {
       
     }
}
