import { Store } from '@ngrx/store';
import { Component, OnInit } from '@angular/core';
import { Product } from '../../interfaces/product';
import { selectProducts } from '../../ngrx/data.slice';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-details',
  standalone: true,
  imports: [CommonModule,FormsModule],
  templateUrl: './details.component.html',
  styleUrl: './details.component.css'
})
export class DetailsComponent  {

  products: Product [] = [];

  constructor(private store:Store){}

  
}
