import { Injectable } from '@angular/core';
import { Orden } from '../models/orden';
import { Http,Response} from '@angular/http';
import { Observable } from 'rxjs';
import { map, filter, catchError, mergeMap } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class OrdenService {
  baseUrl='http://192.168.100.8:8080/ServiceSistemaGestion/public/api/angular';
  constructor(private http:Http) { }

  getOrdenes():Observable<Orden[]>{
    return this.http.get(this.baseUrl+"/ordenes").pipe(map((e:Response)=> e.json()));
  }

  addCsvFiles(file:object):Observable<Orden[]>{
      return this.http.post(this.baseUrl+"/ordenes",file).pipe(map((e:Response)=> e.json()));
  }

  getActivitiesToDay(fecha,tecnico,actividad,estado):Observable<Orden[]>{
    return this.http.get(this.baseUrl+"/actividades-fecha/"+fecha+"/"+tecnico+"/"+actividad+"/"+estado).pipe(map((e:Response)=> e.json()));
  }
  getCantones(tipo):Observable<Orden[]>{
    return this.http.get(this.baseUrl+"/cantones/"+tipo).pipe(map((e:Response)=> e.json()));
  }
  // obtiene sectores desde servicio .. parametro canton type: get
  getSectoresService(tipo,canton):Observable<Orden[]>{
    return this.http.get(this.baseUrl+"/sectores/"+tipo+"/"+canton).pipe(map((e:Response)=> e.json()));
  }
  //obtiene cantidad de actividades 
  getActivitiesCount(tipo,canton,sector):Observable<Orden[]>{
    return this.http.get(this.baseUrl+"/cantidad-actividades/"+tipo+"/"+canton+"/"+sector).pipe(map((e:Response)=> e.json()));
  }
  // actualizar reconexiones manuales
  validarReconexionesManuales():Observable<any>{
    return this.http.get(this.baseUrl+"/validar-rec").pipe(map((e:Response)=> e.json()));
  }
  //obtener rec manuales pendientes
  getRecManualesSinProcesar():Observable<any>{
    return this.http.get(this.baseUrl+"/cont-rec").pipe(map((e:Response)=> e.json()));
  }


  
}