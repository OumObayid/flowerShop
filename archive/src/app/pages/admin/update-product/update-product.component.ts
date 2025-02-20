import { CommonModule } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Store } from '@ngrx/store';
import { ActivatedRoute, Params } from '@angular/router';
import { selectProducts } from '../../../ngrx/data.slice';
import { map } from 'rxjs';
import { Product } from '../../../interfaces/product';
import { ProductsService } from '../../../services/product/products.service';

@Component({
  selector: 'app-update-product',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './update-product.component.html',
  styleUrls: ['./update-product.component.css'],
})
export class UpdateProductComponent implements OnInit {
  constructor(private productService: ProductsService,private store: Store, private route: ActivatedRoute) {}

  // product: Product | undefined ;
  id: number | null = null;
  nom:string="";
  description:string="";
  prix:number=0;
  image:string|null=null;


  ngOnInit() {
    this.id = +this.route.snapshot.paramMap.get('id')!;
    // console.log('this.id :', this.id);
    this.store
      .select(selectProducts)
      .pipe(
        map((products) =>
          products.find((p) => Number(p.id) === Number(this.id))
        )
      )
      .subscribe((prod) => 
    {
      this.nom=prod!.nom;
      this.description=prod!.description;
      this.prix=prod!.prix;
      this.image=prod!.image;
      
    }
    
    );
  }

  // Fonction pour mettre à jour le produit
  updateProduct() {
    
   
  }

  // Annuler la modification
  cancelEdit() {
   
  }

  
  
}
