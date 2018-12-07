import { Injectable } from '@angular/core';
import { Tecnico } from '../models/tecnico';
import { Http,Response,Headers} from '@angular/http';
import { Observable } from 'rxjs';
import { map, filter, catchError, mergeMap } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class TecnicoService {

  headers=new Headers();
  baseUrl="http://gestiondcyk.tecnosolutionscorp.com/api/angular";
  //baseUrl="http://localhost/AppIonicLaravel-Empresa/ServiceSistemaGestion/public/api/angular";
  //baseUrl="http://localhost:8000/api/angular";
  //baseUrl='http://pruebascortes.tecnosolutionscorp.com/api/angular';
  constructor(private http:Http) {
    this.headers.append('Authorization','Bearer '+localStorage.getItem("token"));
   }

  //metodo obtiene todos los tecnicos del servidor
  getAllTecnicos():Observable<Tecnico[]>{
    return this.http.get(this.baseUrl+"/tecnicos",{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }
  //metodo tecnicos con actividades
  getTecnicosSinActividades():Observable<Tecnico[]>{
    return this.http.get(this.baseUrl+"/tecnicos-sin-actividades",{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }
  //metodo obtien por id de tenico
  getTecnicoById(id):Observable<Tecnico>{
    return this.http.get(this.baseUrl+"/get-tecnico/"+id,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }
  //metodo inserta nuevo tecnico en el servidor
  insertTecnico(form:Object):Observable<Tecnico[]> {
    return this.http.post(this.baseUrl+"/tecnicos",form,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }
  //metodo edita y actualiza el técnico
  updateTecnico(form:Object){
    return this.http.post(this.baseUrl+"/update-tecnico",form,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }
  //metodo borra tecnico
  deleteTecnico(id){
    return this.http.get(this.baseUrl+"/delete-tecnico/"+id,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }

  //metodo obtien tecnico por tarea 
  buildTecnicoByTask(dataBuild:object):Observable<any>{
    return this.http.post(this.baseUrl+"/build-task",dataBuild,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }

  // obtener resumen todas las actividades asignadas por tecnico
  getAllActivitiesTecnicos():Observable<any[]>{
    return this.http.get(this.baseUrl+"/actividades-tecnicos",{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }
  // obtiene detalle de actidades por tecnico
  getActivitiesByTecnico(id, tipo):Observable<any>{
    return this.http.get(this.baseUrl+"/actividades-tecnico/"+id+"/"+tipo,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }

  terminarProcesoAvtividades(id){
    return this.http.get(this.baseUrl+"/finalizar/"+id,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }
  //cambiar estado de tecnico 
  changeStateTecnico(id){
    return this.http.get(this.baseUrl+"/cambiar-estado/"+id,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }

  //mostrar detalles de la asignacion de tareas
  showDistribucion():Observable<any[]>{
    return this.http.get(this.baseUrl+"/mostrar-distribucion",{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }

  //metodo obtien tecnico por tarea 
  deleteDistribucion(id_tecn,sector, cantidad):Observable<any>{
    return this.http.get(this.baseUrl+"/delete-distribucion/"+id_tecn+"/"+sector+"/"+cantidad,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
  }

}
