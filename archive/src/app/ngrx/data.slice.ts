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
  users: User[];
}

// État initial
const initialState: DataState = {
  //products
  products: [],
  //user info
  isLoggedIn:
    typeof window !== 'undefined' &&
    localStorage.getItem('isLoggedIn') === 'true',
  //user connecté
  userInfoConnecter:
    typeof window !== 'undefined' && localStorage.getItem('user')
      ? JSON.parse(localStorage.getItem('user')!)
      : null,
  //users
  users: [],
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
export const updateProduct = createAction(
  '[Products] update product',
  (payload: Product) => ({ payload })
);
//users
export const setUsers = createAction(
  '[Users] set users',
  (payload: User[]) => ({ payload })
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

  //Product
  on(setProducts, (state, { payload }) => {
    // console.log("Produits enregistrés dans le store:", payload);
    return {
      ...state,
      products: payload,
    };
  }),
  on(updateProduct, (state, { payload }) => {
    return {
      ...state,
      products: state.products.map((prod) =>
        prod.id === payload.id ? { ...payload } : prod
      ),
    };
  }),
  //get users
  on(setUsers, (state, { payload }) => {
    return {
      ...state,
      users: payload,
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
export const selectProducts = createSelector(selectDataState, (state) => {
  // console.log("Sélecteur selectProducts retourne:", state.products);
  return state.products;
});
export const selectUsers=createSelector(selectDataState,(state)=>{
  return state.users
});
