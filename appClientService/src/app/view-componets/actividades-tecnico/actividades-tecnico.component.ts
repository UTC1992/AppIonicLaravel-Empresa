import { Component, OnInit,ViewChild,ElementRef } from '@angular/core';
import { TecnicoService } from '../../services/tecnico.service';
import { Tecnico } from '../../models/tecnico';
import {FormBuilder} from "@angular/forms";
import { Observable } from 'rxjs';

@Component({
  selector: 'app-actividades-tecnico',
  templateUrl: './actividades-tecnico.component.html',
  styleUrls: ['./actividades-tecnico.component.css']
})
export class ActividadesTecnicoComponent implements OnInit {

  tecnicos:Observable<Tecnico[]>; 
  public loading: boolean;
  res: Observable<any>;
  actividades:Observable<any[]>;


  @ViewChild('inputRef') inputRef: ElementRef;
  constructor(private tecnicoService:TecnicoService) {
    this.loading=false;
   }

  ngOnInit() {
    this.tecnicos =this.tecnicoService.getTecnicosSinActividades();
    this.actividades=this.tecnicoService.getAllActivitiesTecnicos();
  }

  buildTask(tipo){
    
    var cadena="";  
    var mensaje="";
    var cont=0;
    var result = document.getElementsByClassName("tec");
    for(var i=0; i < result.length; i++){ 
      if(<HTMLInputElement>result[i]['checked']){
        var id_tecn =result[i].getAttribute("id");
        cadena+=id_tecn+"&&";  
        cont++;
      }    
    }
    if(cont>0){
      this.loading=true;
      if(cadena!=""){
        cadena=cadena.substring(0,cadena.length-2);         
      }
  
      this.tecnicoService.buildTecnicoByTask(tipo,cadena).subscribe(
          msj=>{
            mensaje=msj;
            console.log("mensaje servidor: "+mensaje);
            if(mensaje){
                alert("Actividades asignadas correctamente");
                this.loading=false;
                this.reloadComponent();
            }else if(!mensaje){
              this.loading=false;
              alert("La actividad ya fue asignada o no existe actividad que asignar");
            }else if(mensaje=="1"){
              this.loading=false;
              alert("Ocurrio un error");
            }
          }  
        );  
    }else{
      alert("Seleccione al menos un técnico");
    }  
  }

  reloadComponent(){
    this.tecnicos =this.tecnicoService.getTecnicosSinActividades();
    this.actividades=this.tecnicoService.getAllActivitiesTecnicos();
  }
 

}
