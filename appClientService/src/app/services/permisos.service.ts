import { Injectable } from '@angular/core';
import { Modulo } from '../models/modulo';
import { HttpClient, HttpHeaders} from '@angular/common/http';
import { Observable, from, throwError } from 'rxjs';
import { map, filter, catchError, mergeMap } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class PermisosService {

  //baseUrl='http://pruebas.tiendanaturalecuador.online/api/angular';
  //baseUrl="http://gestiondcyk.tecnosolutionscorp.com/api/angular";
  private baseUrl="http://localhost:8000/api";
  //baseUrl='http://pruebascortes.tecnosolutionscorp.com/api/angular';
  constructor(
    private http:HttpClient
    ) {
   }

   getModulos():Observable<Modulo[]>{
    return this.http.get<Modulo[]>(this.baseUrl+"/modulos")
    .pipe(catchError( e => {
      if(e.error.mensaje){
        console.error(e.error.mensaje);
      }
      return throwError(e);
    })
    );
   }

   getPlan():Observable<any>{
    return this.http.get<any>(this.baseUrl+"/angular/planes")
    .pipe(catchError( e => {
      if(e.error.mensaje){
        console.error(e.error.mensaje);
      }
      return throwError(e);
    })
    );
   }

}
