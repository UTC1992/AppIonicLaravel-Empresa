import { Injectable } from '@angular/core';
import { Http,Response,Headers} from '@angular/http';
import { Observable } from 'rxjs';
import { map, filter, catchError, mergeMap } from 'rxjs/operators';
import { Usuario } from '../models/usuario';

@Injectable({
  providedIn: 'root'
})
export class PerfilService {
  headers=new Headers();
  //baseUrl="http://localhost:8000/api";
  //baseUrl='http://pruebascortes.tecnosolutionscorp.com/api';
  //baseUrl='http://gestiondcyk.tecnosolutionscorp.com/api';
  baseUrl='http://pruebas.tiendanaturalecuador.online/api';
  constructor(private http:Http) {
    this.headers.append('Authorization','Bearer '+localStorage.getItem("token"));
   }
   // edit Company
   editarEmpresa(form:object){
    return this.http.post(this.baseUrl+"/editEmpresa",form,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
   }
   // edit name
   editarNombre(form:object){
    return this.http.post(this.baseUrl+"/editNombre",form,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
   }
   //editar email
   editarEmail(form:object){
    return this.http.post(this.baseUrl+"/editEmail",form,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
   }
   // editar password
   editarPassword(form:object){
    return this.http.post(this.baseUrl+"/editPassword",form,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
   }
}
