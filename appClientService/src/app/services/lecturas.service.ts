import { Injectable } from '@angular/core';
import { HttpClient,HttpHeaders} from '@angular/common/http';
import { Observable, from, throwError } from 'rxjs';
import { map, filter, catchError, mergeMap } from 'rxjs/operators';
import { Filtro } from "../models/filtro";
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root'
})
export class LecturasService {
  
  baseUrl='http://pruebas.tiendanaturalecuador.online/api/angular';
  //baseUrl='http://gestiondcyk.tecnosolutionscorp.com/api/angular';
  //baseUrl='http://localhost:8000/api/angular';
  //baseUrl='http://pruebascortes.tecnosolutionscorp.com/api/angular';
  constructor(
    private http:HttpClient,
    private route: Router,
    ) {
    
   }

   uploadFile(file:object):Observable<any>{
    return this.http.post(this.baseUrl+"/upload",file).pipe(map((e:Response)=> e.json()));
   }

   /** 
    * obtiene campos filtro de distribucion
    */
   getFilterFields():Observable<Filtro[]>{
    return this.http.get<Filtro[]>(this.baseUrl+"/filtros")
    .pipe(catchError( e => {
      if(e.error.mensaje){
        console.error(e.error.mensaje);
      }
      return throwError(e);
    })
    );
   }

   /**
    * Obtener filtros de primer orden
    */
   getFirstFilterFields():Observable<Filtro[]>{
    return this.http.get<Filtro[]>(this.baseUrl+"/data-first")
    .pipe(catchError( e => {
      if(e.error.mensaje){
        console.error(e.error.mensaje);
      }
      return throwError(e);
    })
    );
   }

   /**
    * obtiene parametros de filtro + 1
    */
   getDataFilter(data:object):Observable<Filtro[]>{
    return this.http.post(this.baseUrl+"/data",data)
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

   /** 
    * otiene id lecturas para distribuir
    */
    getDataDistribution(data:object):Observable<Filtro[]>{
      return this.http.post(this.baseUrl+"/data-distribution",data)
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

    /** 
     * distribuir rutas
     */
    distribuirRutasTecnico(data:object):Observable<any>{
      return this.http.post(this.baseUrl+"/distribution",data)
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
