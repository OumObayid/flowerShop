import { Store } from '@ngrx/store';
import { Component, OnInit } from '@angular/core';
import { Product } from '../../interfaces/product';
import { selectProducts } from '../../ngrx/data.slice';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-home',
  standalone: true,
  imports: [CommonModule,FormsModule,RouterLink],
  templateUrl: './home.component.html',
  styleUrl: './home.component.css'
})
export class HomeComponent implements OnInit {

  products: Product [] = [];

  constructor(private store:Store){}


  ngOnInit(): void {
    this.store.select(selectProducts).subscribe(data => {
      // console.log("Data from store:", data); 
      if (Array.isArray(data)) { 
        this.products = data;
      } else {
        console.error("Error: products is not an array!", data);
        this.products = []; 
      }
    });
  }
  
  
}
