import { Routes } from '@angular/router';
import { HeaderComponent } from './components/header/header.component';
import { HomeComponent } from './pages/home/home.component';
import { AboutComponent } from './pages/about/about.component';
import { LoginComponent } from './pages/auth/login/login.component';
import { RegisterComponent } from './pages/auth/register/register.component';
import { ContactComponent } from './pages/contact/contact.component';
import { DashboardComponent } from './pages/dashboard/dashboard.component';
import { authGuard } from './guards/auth.guard';
import { DetailsComponent } from './pages/details/details.component';
import { UsersComponent } from './pages/admin/users/users.component';
import { ProductsComponent } from './pages/admin/products/products.component';
import { CategoriesComponent } from './pages/admin/categories/categories.component';

export const routes: Routes = [
  { path: '', component: HomeComponent },
  { path: 'details', component: DetailsComponent },
  { path: 'about', component: AboutComponent },
  { path: 'contact', component: ContactComponent },
  { path: 'login', component: LoginComponent },
  { path: 'register', component: RegisterComponent },
  { path: 'users', component: UsersComponent },
  { path: 'products', component: ProductsComponent },
  { path: 'categories', component: CategoriesComponent },
  //Admin
  {
    path: 'dashboard',
    component: DashboardComponent,
    canActivate: [authGuard],

    children: [
      { path: '', redirectTo: 'users', pathMatch: 'full' }, 
      { path: 'users', component: UsersComponent },
      { path: 'products', component: ProductsComponent },
      { path: 'categories', component: CategoriesComponent },
    ],
  },
];
