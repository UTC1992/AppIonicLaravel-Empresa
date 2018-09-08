import { Injectable } from '@angular/core';
import { Orden } from '../models/orden';
import { Http,Response} from '@angular/http';
import { Observable } from 'rxjs';
import { map, filter, catchError, mergeMap } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class OrdenService {
  urlServe='http://192.168.100.144:8080/ServiceSistemaGestion/public/api/angular/ordenes';
  constructor(private http:Http) { }

  getOrdenes():Observable<Orden[]>{
    return this.http.get(this.urlServe).pipe(map((e:Response)=> e.json()));
  }

  addCsvFiles(file:object):Observable<Orden[]>{
      return this.http.post('http://192.168.100.144:8080/ServiceSistemaGestion/public/api/angular/ordenes',file).pipe(map((e:Response)=> e.json()));
  }
}