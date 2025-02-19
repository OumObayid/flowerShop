import {
  createAction,
  createReducer,
  on,
  createFeatureSelector,
  createSelector,
} from '@ngrx/store';
import { User } from '../interfaces/user';
import { Product } from '../interfaces/product';

// Interface pour l'état global
export interface DataState {
  isLoggedIn: boolean;
  userInfoConnecter: User | null;
  products: Product[];
}

// État initial
const initialState: DataState = {
  products: [],
  isLoggedIn:
    typeof window !== 'undefined' &&
    localStorage.getItem('isLoggedIn') === 'true',
  userInfoConnecter:
    typeof window !== 'undefined' && localStorage.getItem('user')
      ? JSON.parse(localStorage.getItem('user')!)
      : null,
};

// -----------------------Actions-------------------------------
//isLoggedIn
export const setActiveUser = createAction('[Auth] Set Active User');
export const removeActiveUser = createAction('[Auth] Remove Active User');
export const getActiveUserInfo = createAction(
  '[User] Get Active User Info',
  (payload: User) => ({ payload })
);
//product
export const setProducts = createAction(
  '[Products] set Products',
  (payload: Product[]) => ({ payload })
);

// ----------------------Reducers-------------------------------
export const dataReducer = createReducer(
  initialState,
  //isLoggedIn  Mettre à jour localStorage
  on(setActiveUser, (state) => {
    if (typeof window !== 'undefined') {
      localStorage.setItem('isLoggedIn', 'true');
    }
    return { ...state, isLoggedIn: true };
  }),
  on(removeActiveUser, (state) => {
    if (typeof window !== 'undefined') {
      localStorage.removeItem('isLoggedIn');
      localStorage.removeItem('user');
    }
    return { ...state, isLoggedIn: false, userInfoConnecter: null };
  }),
  on(getActiveUserInfo, (state, { payload }) => {
    if (typeof window !== 'undefined') {
      localStorage.setItem('user', JSON.stringify(payload));
    }
    return { ...state, userInfoConnecter: payload };
  }),
  //  products
  // on(setProducts, (state, { payload }) => ({
  //   ...state,
  //   products: payload,
  // })),
  on(setProducts, (state, { payload }) => {
    console.log("Produits enregistrés dans le store:", payload); // ✅ شوف واش البيانات تخزنت
    return { 
      ...state, 
      products: payload 
    };
  })
  
  
);

// Sélecteurs
export const selectDataState = createFeatureSelector<DataState>('data');
export const selectIsLoggedIn = createSelector(
  selectDataState,
  (state) => state.isLoggedIn
);
export const selectUserInfoConnecter = createSelector(
  selectDataState,
  (state) => state.userInfoConnecter
);
// export const selectProducts = createSelector(
//   selectDataState,
//   (state) => state.products
// );
export const selectProducts = createSelector(
  selectDataState,
  (state) => {
    console.log("Sélecteur selectProducts retourne:", state.products); 
    return state.products;
  }
);

