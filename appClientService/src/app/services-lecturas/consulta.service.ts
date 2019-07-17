import { Injectable } from '@angular/core';
import { Tecnico } from '../models/tecnico';
import { HttpClient,HttpHeaders} from '@angular/common/http';
import { Observable, from, throwError } from 'rxjs';
import { map, filter, catchError, mergeMap } from 'rxjs/operators';
import { Url } from '../models/Url';

@Injectable({
  providedIn: 'root'
})
export class ConsultaService {

  url: Url = new Url();
  baseUrl: string;

  constructor(
    private http:HttpClient,
    ) {
      this.baseUrl = this.url.base;
   }

   getDataProgreso(data:object):Observable<any>{
    return this.http.post<any>(this.baseUrl+"/reportes/lecturas",data)
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
