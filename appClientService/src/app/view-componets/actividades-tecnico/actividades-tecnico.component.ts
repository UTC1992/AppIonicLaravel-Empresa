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
  loading: boolean = false;
  res: Observable<any>;
  actividades:Observable<any[]>;


  @ViewChild('inputRef') inputRef: ElementRef;
  constructor(private tecnicoService:TecnicoService) { }

  ngOnInit() {
    this.tecnicos =this.tecnicoService.getAllTecnicos();
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
      if(cadena!=""){
        cadena=cadena.substring(0,cadena.length-2);         
      }
  
      this.tecnicoService.buildTecnicoByTask(tipo,cadena).subscribe(
          msj=>{
            mensaje=msj;
            console.log("mensaje servidor: "+mensaje);
            if(mensaje){
                alert("Actividades asignadas correctamente");
                location.reload();
            }else{
              alert("Ocurrio un error");
            }
          }  
        );  
    }else{
      alert("Seleccione al menos un tecnico");
    }  
  }

  reloadPage(){
    location.reload();
  }
 

}
