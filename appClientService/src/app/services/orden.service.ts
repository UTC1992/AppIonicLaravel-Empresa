import { Injectable } from '@angular/core';
import { Orden } from '../models/orden';
import { Http,Response,Headers} from '@angular/http';
import { Observable } from 'rxjs';
import { map, filter, catchError, mergeMap } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class OrdenService {
  headers=new Headers();
  
  //baseUrl='http://pruebas.tiendanaturalecuador.online/api/angular';
  //baseUrl='http://gestiondcyk.tecnosolutionscorp.com/api/angular';
  baseUrl='http://localhost:8000/api/angular';
  //baseUrl='http://pruebascortes.tecnosolutionscorp.com/api/angular';
  constructor(private http:Http) {
    this.headers.append('Authorization','Bearer '+localStorage.getItem("token"));
   }

  getOrdenes():Observable<Orden[]>{
    return this.http.get(this.baseUrl+"/ordenes",{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }

  addCsvFiles(file:object):Observable<Orden[]>{
      return this.http.post(this.baseUrl+"/ordenes",file,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }

  getActivitiesToDay(fecha,tecnico,actividad,estado):Observable<Orden[]>{
    return this.http.get(this.baseUrl+"/actividades-fecha/"+fecha+"/"+tecnico+"/"+actividad+"/"+estado,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }
  getCantones(tipo):Observable<Orden[]>{
    return this.http.get(this.baseUrl+"/cantones/"+tipo,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }
  // obtiene sectores desde servicio .. parametro canton type: get
  getSectoresService(tipo,canton):Observable<Orden[]>{
    return this.http.get(this.baseUrl+"/sectores/"+tipo+"/"+canton,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }
  //obtiene cantidad de actividades 
  getActivitiesCountSec(datos:object):Observable<Orden[]>{
    return this.http.post(this.baseUrl+"/cantidad-post",datos,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }
  // actualizar reconexiones manuales
  validarReconexionesManuales():Observable<any>{
    return this.http.get(this.baseUrl+"/validar-rec",{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }
  //obtener rec manuales pendientes
  getRecManualesSinProcesar():Observable<any>{
    return this.http.get(this.baseUrl+"/cont-rec",{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }
  //consolidar actividades diarias
  consolidarActividades(date):Observable<any>{
    return this.http.get(this.baseUrl+"/consolidar-actividades/"+date,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }
  //obtner actividades consolidadas por fecha: paremeter date
  obtenerCosolidadosDelDia(date):Observable<Orden[]>{
    return this.http.get(this.baseUrl+"/actividades-consolidadas/"+date,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }
  // borrar actividades del dia
  deleteActivities(fecha:object):Observable<Orden[]>{
    return this.http.post(this.baseUrl+"/delete-activities",fecha,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }
    
  getRecManual(datos:object):Observable<any[]>{
    return this.http.post(this.baseUrl+"/get-rec-manual",datos,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }

}