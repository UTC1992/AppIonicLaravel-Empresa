import { Component, OnInit,ViewChild,ElementRef } from '@angular/core';
import { TecnicoService } from '../../services/tecnico.service';
import { OrdenService } from '../../services/orden.service';
import { Tecnico } from '../../models/tecnico';
import { Orden } from '../../models/orden';
import { TecnicoDistribucion } from '../../models/tecnico-distribucion';
import { Observable } from 'rxjs';
import { Type } from '@angular/compiler';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";


@Component({
  selector: 'app-actividades-tecnico',
  templateUrl: './actividades-tecnico.component.html',
  styleUrls: ['./actividades-tecnico.component.css']
})
export class ActividadesTecnicoComponent implements OnInit {
  countryForm: FormGroup;
  tecnicos:Observable<Tecnico[]>; 
  public loading: boolean;
  res: Observable<any>;
  actividades:Observable<any[]>;
  public view_table:boolean;
  actividades_tecncio:Observable<any>;
  key: string = 'name'; //set default
  reverse: boolean = false;
  p: number = 1;
  cantones: Observable<Orden[]>;
  sectores:Observable<Orden[]>;
  cantones_exists:boolean;
  num_cantones:number=0;
  sectores_exists:boolean;
  num_sectores:number=0;
  num_actividades:number=0;
  cantidad_exists:boolean;
  cantidad:Observable<Orden[]>;
  Ordenes:Number;
  

  objTecnicoDistribucion:TecnicoDistribucion;


  @ViewChild('inputRef') inputRef: ElementRef;
  constructor(private tecnicoService:TecnicoService, private ordenServices:OrdenService,private fb: FormBuilder) {
    this.loading=false;
    this.view_table=false;
    this.cantones_exists=false;
    this.sectores_exists=false;
    this.cantidad_exists=false;
    this.createForm();
   }

  ngOnInit() {
    this.tecnicos =this.tecnicoService.getTecnicosSinActividades();
    this.actividades=this.tecnicoService.getAllActivitiesTecnicos();
    this.countActivities();
  }
  //contar total cantidades por asignar
  countActivities(){
    this.ordenServices.getOrdenes().subscribe(
      resul=>{
        this.Ordenes=resul.length;
      }
    );
  }
  createForm() {
    this.countryForm = this.fb.group({
      actividad: "empty"
    });
  }
  sort(key){
    this.key = key;
    this.reverse = !this.reverse;
  }

  // recargar componentes
  reloadComponent(){
    this.tecnicos =this.tecnicoService.getTecnicosSinActividades();
    this.actividades=this.tecnicoService.getAllActivitiesTecnicos();
  }
 
  //ver detalle en modal 
  verDetalleActividades(id) {
    this.p = 1;
    this.actividades_tecncio=this.tecnicoService.getActivitiesByTecnico(id);
    this.view_table=true;

  }

  AsignarActividades(id){
    this.tecnicoService.changeStateTecnico(id).subscribe(
      msj=>{
        if(msj){
          alert("Técnico habilitado correctamente");
          this.reloadComponent();
        }
      }
    );
  }

  getTypeActivities($event){
    let valor=$event.target.value;
    this.cantones=this.ordenServices.getCantones(valor);
    if(valor=="empty"){
      this.cantones_exists=false;
      this.sectores_exists=false;
      this.cantidad_exists=false;
    }else{
      this.cantones.subscribe(
        result=>{
          this.cantones_exists=true;
          this.num_cantones=result.length;
          console.log(result);
        }
      );
    }  
  }

  // obtiene sectores desde el servicio
  getSectors($event){
    let valor=$event.target.value;
    var result = document.getElementsByName("actividad");
    var actividad=<HTMLInputElement>result[0]["value"];
    this.sectores=this.ordenServices.getSectoresService(actividad,valor);
    if(valor=="empty"){
      this.sectores_exists=false;
    }else{
      this.sectores.subscribe(
        result=>{
          this.sectores_exists=true;
          this.num_sectores=result.length;
          console.log(result);
        }
      );
    }
  }
// obtiene sectores desde el servicio
getCantidadSectores($event){
  let sector=$event.target.value;
 // alert(sector);
  var result = document.getElementsByName("actividad");
  var actividad=<HTMLInputElement>result[0]["value"];
  //alert(actividad);
  var res = document.getElementsByName("canton");
  var canton=<HTMLInputElement>res[0]["value"];
  //alert(canton);
  this.cantidad=this.ordenServices.getActivitiesCount(actividad,canton,sector);
  if(sector=="empty"){
    //this.cantidad_exists=false;
    this.num_actividades=0;

  }else{
    
    this.cantidad.subscribe(
      resultado=>{
        this.cantidad_exists=true;
        this.num_actividades=resultado.length;
        
        //console.log("actividades: "+resultado[0]['id_act']);
      }
    );
    
    //console.log("actividades: "+this.cantidad);
  }
}

    //distribuir actividades tecnico
    buildTask(){
      this.ordenServices.getRecManualesSinProcesar().subscribe(
        resultado=>{
          if(resultado>0){
            alert("Aun no ha procesado las reconexiones manuales");
            return;
          }else{
            var re= document.getElementsByName("actividad");
    var actividad=<HTMLInputElement>re[0]["value"];
    if(cont_tecnicos<=0){
      alert("Seleccione almenos un tecnico");
      return;
    }
    if(!this.cantones_exists){
      alert("Seleccione un cantón");
      return;
    }
    if(!this.sectores_exists){
      alert("Seleccione un sector");
      return;
    }
    if(!this.cantidad_exists){
      alert("NO ha seccionado actividades");
      return;
    }

    var cant=document.getElementsByName("cantidad_actividades");
    var cantidad_actividades=<HTMLInputElement>cant[0]["value"];
    
    this.cantidad.subscribe(
      msj=>{
        //alert(msj.length);
        if(msj.length<=0){
          alert("seleccione actividades a distribuir");
          return;
        }
      this.loading=true;
       msj.forEach(element => {
         array_actividades[cont]=element["id_act"];
         cont++;
       });
       this.tecnicoService.buildTecnicoByTask(array_actividades,array_tecnicos,actividad,cantidad_actividades).subscribe(
         result=>{
            if(result){
              this.loading=false;
              this.reloadComponent();
              this.cantones_exists=false;
              this.sectores_exists=false;
              this.cantidad_exists=false;
              var re= document.getElementsByName("actividad");
              re[0]["value"]="empty";
              this.countActivities();
            }else if(result==1){
              alert("El  número de actividades no puede ser igual o menor a cero  ");
            }else{
              alert("No se asigno las actividades  ");
            }
            
         }
       );
      }
    );
          }    
        }
      );
      
      var cont_array_tecn=0;
      var array_tecnicos:String[]=[];
      var array_actividades:String[]=[];
      var cont=0;
      var cont_tecnicos=0;
      var result = document.getElementsByClassName("tec");
    for(var i=0; i < result.length; i++){ 
      if(<HTMLInputElement>result[i]['checked']){
        var id_tecn =result[i].getAttribute("id");
        array_tecnicos[cont_array_tecn]=id_tecn;
        cont_tecnicos++;
        cont_array_tecn++;
      }    
    }
    
    
    }

}
