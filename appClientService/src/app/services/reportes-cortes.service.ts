import { Injectable } from '@angular/core';
import { HttpClient,HttpHeaders} from '@angular/common/http';
import { Observable, from, throwError } from 'rxjs';
import { map, filter, catchError, mergeMap } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class ReportesCortes {

  headers=new Headers();
  //baseUrl='http://pruebas.tiendanaturalecuador.online/api/angular';
  //baseUrl="http://gestiondcyk.tecnosolutionscorp.com/api/angular";
  baseUrl="http://localhost:8000/api";
  //baseUrl='http://pruebascortes.tecnosolutionscorp.com/api/angular';
  constructor(
      private http:HttpClient
    ) {
   }

   //metodo edita y actualiza el técnico
  getCortesDiarios(data:Object){
    return this.http.post(this.baseUrl+"/reportes/cortes-diario",data)
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
