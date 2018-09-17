import { Component, OnInit,ViewChild,ElementRef } from '@angular/core';
import { TecnicoService } from '../../services/tecnico.service';
import { Tecnico } from '../../models/tecnico';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import { Observable } from 'rxjs';

@Component({
  selector: 'app-actividades-tecnico',
  templateUrl: './actividades-tecnico.component.html',
  styleUrls: ['./actividades-tecnico.component.css']
})
export class ActividadesTecnicoComponent implements OnInit {

  tecnicos:Observable<Tecnico[]>; 
  loading: boolean = false;

  @ViewChild('inputRef') inputRef: ElementRef;
  constructor(private tecnicoService:TecnicoService,private formBuilder: FormBuilder) { }

  ngOnInit() {
    this.tecnicos =this.tecnicoService.getAllTecnicos();
  }

  buildTask(tipo){
    var cadena="";
    var result = document.getElementsByClassName("tec");
    for(var i=0; i < result.length; i++){ 
      //alert(<HTMLInputElement>result[i]['checked']);
      if(<HTMLInputElement>result[i]['checked']){
        var id_tecn =result[i].getAttribute("id");
        cadena+=id_tecn+"&&";  
      }    
    }
    if(cadena!=""){
      cadena=cadena.substring(0,cadena.length-2);         
    }

    var res=this.tecnicoService.buildTecnicoByTask(tipo,cadena);
    setTimeout(() => {
      alert('Tareas Asignadas correctamente!');
      //this.loading = false;
      //this.clearFile();
      location.reload();
    }, 1000);
  }


}
