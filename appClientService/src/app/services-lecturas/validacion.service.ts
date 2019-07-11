import { Injectable } from '@angular/core';
import { HttpClient,HttpHeaders} from '@angular/common/http';
import { Observable, from, throwError } from 'rxjs';
import { map, filter, catchError, mergeMap } from 'rxjs/operators';
import { Router } from '@angular/router';
import { Url } from '../models/Url';

@Injectable({
  providedIn: 'root'
})
export class ValidacionService {
  url: Url = new Url();
  baseUrl= this.url.base;
  
  constructor(
    private http:HttpClient,
    private route: Router,
    ) {
    
   }

  calcularConsumos(): Observable<any[]>{
    return this.http.get<any[]>(this.baseUrl+"/procesos/calcula-consumos")
    .pipe(catchError( e => {
      if(e.error.mensaje){
        //console.error(e.error.mensaje);
      }
      return throwError(e);
    })
    );
  }

  validarConsumos(): Observable<any[]>{
    return this.http.get<any[]>(this.baseUrl+"/procesos/valida-consumos")
    .pipe(catchError( e => {
      if(e.error.mensaje){
        //console.error(e.error.mensaje);
      }
      return throwError(e);
    })
    );
  }

  validarLecturasCero(): Observable<any[]>{
    return this.http.get<any[]>(this.baseUrl+"/procesos/valida-lecturas")
    .pipe(catchError( e => {
      if(e.error.mensaje){
        //console.error(e.error.mensaje);
      }
      return throwError(e);
    })
    );
  }
  
}
