import { ProductsService } from './services/product/products.service';
import { Component } from '@angular/core';
import { Router, RouterLink, RouterOutlet } from '@angular/router';
import { HeaderComponent } from './components/header/header.component';
import { FooterComponent } from './components/footer/footer.component';
import { CommonModule } from '@angular/common';
import { Product } from './interfaces/product';
import { Store } from '@ngrx/store';
import { setProducts } from './ngrx/data.slice';

@Component({
  selector: 'app-root',
  imports:[RouterOutlet,HeaderComponent,FooterComponent],
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss'],
})
export class AppComponent {
  title = 'flowersOum';
   products: Product | null = null;
   
     constructor(private store:Store, private productsService:ProductsService){}
   
    ngOnInit(): void {
      this.productsService.getProducts().subscribe((response: any) => {
        console.log("Produits récupérés depuis l'API dans app:", response); 
        if (response.success && Array.isArray(response.products)) {
          this.store.dispatch(setProducts(response.products)); 
        } else {
          console.error("Erreur: Format de données invalide!", response);
        }
      });
    }
    
}
