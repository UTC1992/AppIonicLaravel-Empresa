import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders} from '@angular/common/http';
import { Observable, from, throwError } from 'rxjs';
import { map, filter, catchError, mergeMap } from 'rxjs/operators';
import { Usuario } from '../models/usuario';

@Injectable({
  providedIn: 'root'
})
export class PerfilService {
  headers=new Headers();
  
  //baseUrl='http://pruebas.tiendanaturalecuador.online/api';
  baseUrl="http://192.168.1.4:8000/api";
  //baseUrl='http://pruebascortes.tecnosolutionscorp.com/api';
  //baseUrl='http://gestiondcyk.tecnosolutionscorp.com/api';
  
  constructor(private http:HttpClient) {
    this.headers.append('Authorization','Bearer '+localStorage.getItem("token"));
   }
   // edit Company
   editarEmpresa(form:object){
    return this.http.post(this.baseUrl+"/editEmpresa",form)
    .pipe(
      map((response: any) => response),
      catchError(e => {

        if(e.status == 400){
          return throwError(e);
        }

        if(e.error.mensaje){
          console.error(e.error.mensaje);
        }
        return throwError(e);
      })
    );
   }
   // edit name
   editarNombre(form:object){
    return this.http.post(this.baseUrl+"/editNombre",form)
    .pipe(
      map((response: any) => response),
      catchError(e => {

        if(e.status == 400){
          return throwError(e);
        }

        if(e.error.mensaje){
          console.error(e.error.mensaje);
        }
        return throwError(e);
      })
    );
   }
   //editar email
   editarEmail(form:object){
    return this.http.post(this.baseUrl+"/editEmail",form)
    .pipe(
      map((response: any) => response),
      catchError(e => {

        if(e.status == 400){
          return throwError(e);
        }

        if(e.error.mensaje){
          console.error(e.error.mensaje);
        }
        return throwError(e);
      })
    );
   }
   // editar password
   editarPassword(form:object){
    return this.http.post(this.baseUrl+"/editPassword",form)
    .pipe(
      map((response: any) => response),
      catchError(e => {

        if(e.status == 400){
          return throwError(e);
        }

        if(e.error.mensaje){
          console.error(e.error.mensaje);
        }
        return throwError(e);
      })
    );
   }
}
