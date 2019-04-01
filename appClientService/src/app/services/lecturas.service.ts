import { Injectable } from '@angular/core';
import { Http,Response,Headers} from '@angular/http';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import { Filtro } from "../models/filtro";

@Injectable({
  providedIn: 'root'
})
export class LecturasService {
  headers=new Headers();
  
  //baseUrl='http://pruebas.tiendanaturalecuador.online/api/angular';
  //baseUrl='http://gestiondcyk.tecnosolutionscorp.com/api/angular';
  baseUrl='http://localhost:8000/api/angular';
  //baseUrl='http://pruebascortes.tecnosolutionscorp.com/api/angular';
  constructor(private http:Http) {
    this.headers.append('Authorization','Bearer '+localStorage.getItem("token"));
   }

   uploadFile(file:object):Observable<any>{
    return this.http.post(this.baseUrl+"/upload",file,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
   }

   /** 
    * obtiene campos filtro de distribucion
    */
   getFilterFields():Observable<Filtro[]>{
    return this.http.get(this.baseUrl+"/filtros",{headers:this.headers}).pipe(map((e:Response)=> e.json()));
   }

   /**
    * Obtener filtros de primer orden
    */
   getFirstFilterFields():Observable<Filtro[]>{
    return this.http.get(this.baseUrl+"/data-first",{headers:this.headers}).pipe(map((e:Response)=> e.json()));
   }

   /**
    * obtiene parametros de filtro + 1
    */
   getDataFilter(data:object):Observable<Filtro[]>{
    return this.http.post(this.baseUrl+"/data",data,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
   }

   /** 
    * otiene id lecturas para distribuir
    */
    getDataDistribution(data:object):Observable<Filtro[]>{
      return this.http.post(this.baseUrl+"/data-distribution",data,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
    }

    /** 
     * distribuir rutas
     */
    distribuirRutasTecnico(data:object):Observable<any>{
      return this.http.post(this.baseUrl+"/distribution",data,{headers:this.headers}).pipe(map((e:Response)=> e.json()));
    }
  
}
