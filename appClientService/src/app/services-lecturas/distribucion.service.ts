import { Injectable } from '@angular/core';
import { HttpClient,HttpHeaders} from '@angular/common/http';
import { Observable, from, throwError } from 'rxjs';
import { map, filter, catchError, mergeMap } from 'rxjs/operators';
import { Filtro } from "../modelos-lec/filtro";
import { RutaTecnico } from "../modelos-lec/ruta_tecnico";
import { Router } from '@angular/router';
import { Url } from '../models/Url';

@Injectable({
  providedIn: 'root'
})
export class DistribucionService {
  url: Url = new Url();
  baseUrl= this.url.base;
  
  constructor(
    private http:HttpClient,
    private route: Router,
    ) {
    
   }

  getRutasAll(): Observable<any[]>{
    return this.http.get<any[]>(this.baseUrl+"/rutas")
    .pipe(catchError( e => {
      if(e.error.mensaje){
        //console.error(e.error.mensaje);
      }
      return throwError(e);
    })
    );
  }

  insertRutaTecnico(data : RutaTecnico[]):Observable<any[]>{
    return this.http.post(this.baseUrl+"/rutas/distribucion",data)
    .pipe(
      map((response: any) => response),
      catchError(e => {

        if(e.status == 400){
          return throwError(e);
        }

        if(e.error.mensaje){
          //console.error(e.error.mensaje);
        }
        return throwError(e);
      })
    );
   }
  
}
