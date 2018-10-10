import { Injectable } from '@angular/core';
import { Tecnico } from '../models/tecnico';
import { Http,Response} from '@angular/http';
import { Observable } from 'rxjs';
import { map, filter, catchError, mergeMap } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class TecnicoService {
  baseUrl="http://192.168.100.227:8080/ServiceSistemaGestion/public/api/angular";
  constructor(private http:Http) { }

  //metodo obtiene todos los tecnicos del servidor
  getAllTecnicos():Observable<Tecnico[]>{
    return this.http.get(this.baseUrl+"/tecnicos").pipe(map((e:Response)=> e.json()));
  }
  //metodo tecnicos con actividades
  getTecnicosSinActividades():Observable<Tecnico[]>{
    return this.http.get(this.baseUrl+"/tecnicos-sin-actividades").pipe(map((e:Response)=> e.json()));
  }
  //metodo obtien por id de tenico
  getTecnicoById(id):Observable<Tecnico>{
    return this.http.get(this.baseUrl+"/get-tecnico/"+id).pipe(map((e:Response)=> e.json()));
  }
  //metodo inserta nuevo tecnico en el servidor
  insertTecnico(form:Object):Observable<Tecnico[]> {
    return this.http.post(this.baseUrl+"/tecnicos",form).pipe(map((e:Response)=> e.json()));
  }
  //metodo edita y actualiza el técnico
  updateTecnico(form:Object){
    return this.http.post(this.baseUrl+"/update-tecnico",form).pipe(map((e:Response)=> e.json()));
  }
  //metodo borra tecnico
  deleteTecnico(id){
    return this.http.get(this.baseUrl+"/delete-tecnico/"+id).pipe(map((e:Response)=> e.json()));
  }
  //metodo obtien tecnico por tarea 
  buildTecnicoByTask(tipo,cadena):Observable<any>{
    return this.http.get(this.baseUrl+"/build-task/"+tipo+"/"+cadena).pipe(map((e:Response)=> e.json()));
  }

  // obtener resumen todas las actividades asignadas por tecnico
  getAllActivitiesTecnicos():Observable<any[]>{
    return this.http.get(this.baseUrl+"/actividades-tecnicos").pipe(map((e:Response)=> e.json()));
  }
  // obtiene detalle de actidades por tecnico
  getActivitiesByTecnico(id):Observable<any>{
    return this.http.get(this.baseUrl+"/actividades-tecnico/"+id).pipe(map((e:Response)=> e.json()));
  }

  terminarProcesoAvtividades(id){
    return this.http.get(this.baseUrl+"/finalizar/"+id).pipe(map((e:Response)=> e.json()));
  }
}
