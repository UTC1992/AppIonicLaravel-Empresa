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
  headers2=new Headers();
  baseUrl='http://gestiondcyk.tecnosolutionscorp.com/';
  //baseUrl='http://localhost:8000/';
  //baseUrl='http://pruebascortes.tecnosolutionscorp.com/';
  constructor(private http:Http) { 
    
  }
  //obtener token de acceso
  autenticarUsuario(usuario:Usuario):Observable<Token>{
    let datos={
      'grant_type':'password',
      'client_id':'2',
      'client_secret':'nYYr0wL8qCuj29M5mN2D3vanaF98dMRsS52KMcM3',
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

  // validar empresa 
  validarEmpresaActiva():Observable<Usuario>{
    this.headers2.append('Authorization','Bearer '+localStorage.getItem("token"));
    return this.http.post(this.baseUrl+"api/empresaActiva",{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }
}
