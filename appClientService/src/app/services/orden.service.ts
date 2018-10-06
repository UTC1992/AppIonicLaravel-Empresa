import { Injectable } from '@angular/core';
import { Orden } from '../models/orden';
import { Http,Response} from '@angular/http';
import { Observable } from 'rxjs';
import { map, filter, catchError, mergeMap } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class OrdenService {
  baseUrl='http://192.168.100.217:8080/ServiceSistemaGestion/public/api/angular';
  constructor(private http:Http) { }

  getOrdenes():Observable<Orden[]>{
    return this.http.get(this.baseUrl+"/ordenes").pipe(map((e:Response)=> e.json()));
  }

  addCsvFiles(file:object):Observable<Orden[]>{
      return this.http.post(this.baseUrl+"/ordenes",file).pipe(map((e:Response)=> e.json()));
  }

  getActivitiesToDay(fecha):Observable<Orden[]>{
    return this.http.get(this.baseUrl+"/actividades-fecha/"+fecha).pipe(map((e:Response)=> e.json()));
  }

  
}