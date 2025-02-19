import { Store } from '@ngrx/store';
import { Component, OnInit } from '@angular/core';
import { Product } from '../../interfaces/product';
import { selectProducts } from '../../ngrx/data.slice';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-home',
  standalone: true,
  imports: [CommonModule,FormsModule],
  templateUrl: './home.component.html',
  styleUrl: './home.component.css'
})
export class HomeComponent implements OnInit {

  products: Product [] = [];

  constructor(private store:Store){}

  // ngOnInit(): void {

  //  this.store.select(selectProducts).subscribe(data=>{
  //   console.log("Data from store:", data); // ✅ شوف شنو راجع من store
  //   this.products=data;
   
  //  });
  // }
  ngOnInit(): void {
    this.store.select(selectProducts).subscribe(data => {
      console.log("Data from store:", data); // ✅ شوف شنو راجع من store
      if (Array.isArray(data)) { // ✅ تأكد بأنه Array
        this.products = data;
      } else {
        console.error("Error: products is not an array!", data);
        this.products = []; // ✅ تفادى الخطأ
      }
    });
  }
  
  
}
