import { Injectable } from '@angular/core';
import { Usuario } from '../models/usuario';
import { Http,Response,Headers } from '@angular/http';
import { Observable } from 'rxjs';
import { map,} from 'rxjs/operators';
import { Token } from '../models/token';



@Injectable({
  providedIn: 'root'
})
export class LoginService {

  headers=new Headers();
  baseUrl='http://localhost:8000/';
  constructor(private http:Http) { 
    
  }
  //obtener token de acceso
  autenticarUsuario(usuario:Usuario):Observable<Token>{
    let datos={
      'grant_type':'password',
      'client_id':'3',
      'client_secret':'Bjtn13nLbxRPqNPRJASpbmufM5PyKAkHoXF7jvpm',
      'username':usuario.username,
      'password':usuario.password,
      'scope':'*'
    }
    return this.http.post(this.baseUrl+"oauth/token",datos).pipe(map((e:Response)=> e.json()));
  }
  //obtener datos usuario
  getUserDataAutenticate():Observable<Usuario>{
    this.headers.append('Authorization','Bearer '+localStorage.getItem("token"));
    return this.http.get(this.baseUrl+"api/usuarioAutenticado",{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }
}
