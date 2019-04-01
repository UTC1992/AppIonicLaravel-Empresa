import { Injectable } from '@angular/core';
import { Modulo } from '../models/modulo';
import { Http,Response,Headers} from '@angular/http';
import { Observable } from 'rxjs';
import { map, filter, catchError, mergeMap } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class PermisosService {

  headers=new Headers();
  //baseUrl='http://pruebas.tiendanaturalecuador.online/api/angular';
  //baseUrl="http://gestiondcyk.tecnosolutionscorp.com/api/angular";
  private baseUrl="http://localhost:8000/api";
  //baseUrl='http://pruebascortes.tecnosolutionscorp.com/api/angular';
  constructor(private http:Http) {
   this.headers.append('Authorization','Bearer '+localStorage.getItem("token"));
   }

   getModulos():Observable<Modulo[]>{
    return this.http.get(this.baseUrl+"/modulos",{headers:this.headers}).pipe(map((e:Response)=> e.json()));
   }



}
