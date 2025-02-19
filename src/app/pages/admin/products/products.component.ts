import { selectProducts } from './../../../ngrx/data.slice';
import { Component } from '@angular/core';
import { Store } from '@ngrx/store';
import { Categorie } from '../../../interfaces/categorie';
import { Product } from '../../../interfaces/product';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-products',
  imports: [CommonModule,FormsModule],
  templateUrl: './products.component.html',
  styleUrl: './products.component.css'
})
export class ProductsComponent {

   products:Product [] | null=null;
    
       constructor(public store:Store){}
    
       ngOnInit(): void {
         this.store.select(selectProducts).subscribe((dataProd)=>{
          this.products=dataProd;
         })
       }
}
