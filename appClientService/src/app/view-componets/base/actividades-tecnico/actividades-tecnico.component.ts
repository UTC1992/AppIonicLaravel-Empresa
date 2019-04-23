import { Component, OnInit,ViewChild,ElementRef } from '@angular/core';
import { TecnicoService } from '../../../services/tecnico.service';
import { OrdenService } from '../../../services/orden.service';
import { Tecnico } from '../../../models/tecnico';
import { Orden } from '../../../models/orden';
import { TecnicoDistribucion } from '../../../models/tecnico-distribucion';
import { Observable } from 'rxjs';
import { Type } from '@angular/compiler';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";

import { IMultiSelectOption } from 'angular-2-dropdown-multiselect';
import { Sector } from 'src/app/models/sector';
import { SectorList } from 'src/app/models/sector-list';
import { NgxSpinnerService } from 'ngx-spinner';

@Component({
  selector: 'app-actividades-tecnico',
  templateUrl: './actividades-tecnico.component.html',
  styleUrls: ['./actividades-tecnico.component.css']
})
export class ActividadesTecnicoComponent implements OnInit {
  optionsModel: number[];
  myOptions: IMultiSelectOption[];
  countryForm: FormGroup;
  tecnicos:Tecnico[] = [];

  public loading: boolean;
  res: Observable<any>;
  actividades:Observable<any[]>;
  public view_table:boolean;
  actividades_tecncio:Observable<any>;
  key: string = 'name'; //set default
  reverse: boolean = false;
  p: number = 1;
  cantones: Observable<Orden[]>;
  sectores:Observable<any[]>;
  cantones_exists:boolean;
  num_cantones:number=0;
  sectores_exists:boolean;
  num_sectores:number=0;
  num_actividades:number=0;
  cantidad_exists:boolean;
  cantidad:Observable<Orden[]>;
  Ordenes:Number;
  distribucion:Observable<any>;
  distribucionDelete:Observable<any>;

  objTecnicoDistribucion:TecnicoDistribucion;

  tecnicoSeleccionado: string;
  listTecnicosSeleccionados: any[];

  @ViewChild('inputRef') inputRef: ElementRef;
  constructor(private tecnicoService:TecnicoService, 
              private ordenServices:OrdenService,
              private fb: FormBuilder,
              private spinner: NgxSpinnerService) {
    this.loading=false;
    this.view_table=false;
    this.cantones_exists=false;
    this.sectores_exists=false;
    this.cantidad_exists=false;
    this.createForm();
    this.spinner.show();
   }

   

  onSelection(e, list){
    this.tecnicoSeleccionado = e.option.value;
    this.listTecnicosSeleccionados = list;
    if(list.length > 0){
      console.log(list[0].value);
    }
    
  }

  ngOnInit() {
    this.mostrarTecnicos();
    this.actividades=this.tecnicoService.showDistribucion();
    this.countActivities();
    //this.mostrarDistribucion();
  }

  mostrarTecnicos(){
    this.tecnicoService.getTecnicosSinActividades().subscribe(res =>{
      //console.log(res);
      this.tecnicos = res;
    });
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
    this.mostrarTecnicos();
    this.actividades=this.tecnicoService.showDistribucion();
    this.countActivities();
  }
 
  //ver detalle en modal 
  verDetalleActividades(id,tipo,sector) {
    this.actividades_tecncio=null;
    this.p = 1;
    switch (tipo) {
      case "Notificaciones":
        this.actividades_tecncio=this.tecnicoService.getActivitiesByTecnico(id, '010',sector);
        console.log(this.actividades_tecncio);
        this.view_table=true;
        break;
      case "Corte":
        this.actividades_tecncio=this.tecnicoService.getActivitiesByTecnico(id, '030',sector);
        console.log(this.actividades_tecncio);
        this.view_table=true;
        break;
      case "Reconexiones":
        this.actividades_tecncio=this.tecnicoService.getActivitiesByTecnico(id, '040',sector);
        console.log(this.actividades_tecncio);
        this.view_table=true;
        break;
      case "Retiro de medidor":
        this.actividades_tecncio=this.tecnicoService.getActivitiesByTecnico(id, '050',sector);
        console.log(this.actividades_tecncio);
        this.view_table=true;
      default:
        // code...
        break;
    }
    

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
          //console.log(result);
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
          this.optionsModel=[];
          this.myOptions=[];
          this.sectores_exists=true;
          this.num_sectores=result.length;
          result.forEach(element => {
            var sector=new Sector();
            sector.id=element.sector;
            sector.name=element.sector;
            this.myOptions.push(sector);
          });
          console.log(result);
        }
      );
    }
  }
  // camptura sectores
  onChange() {
    console.log(this.optionsModel);
    var result = document.getElementsByName("actividad");
    var actividad=<HTMLInputElement>result[0]["value"];
    //alert(actividad);
    var res = document.getElementsByName("canton");
    var canton=<HTMLInputElement>res[0]["value"];
    //alert(canton);
    
    let data={
      'actividad':actividad,
      'canton':canton,
      'sector':this.optionsModel,
    };
    this.cantidad=this.ordenServices.getActivitiesCountSec(data);
    if(this.optionsModel.length<=0){
      //this.cantidad_exists=false;
      this.num_actividades=0;

    }else{
      
      this.cantidad.subscribe(
        resultado=>{
          this.cantidad_exists=true;
          this.num_actividades=resultado.length;
          
        }
      );
      
      //console.log("actividades: "+this.cantidad);
    }
    
  }

    //distribuir actividades tecnico
    buildTask(){
      //console.log("DISTRIBUIR ACTIVIDADES");
      this.spinner.show();
      this.ordenServices.getRecManualesSinProcesar().subscribe(
        resultado=>{
          if(resultado>0){
            alert("Aun no ha procesado las reconexiones manuales");
            this.spinner.hide();
            return;
          }else{
            var re= document.getElementsByName("actividad");
            var actividad=<HTMLInputElement>re[0]["value"];
            
            if(cont_tecnicos<=0){
              alert("Seleccione almenos un tecnico");
              this.spinner.hide();
              return;
            }

            if(actividad+"" == "empty"){
              alert("Seleccione una actividad");
              this.spinner.hide();
              return;
            } else {
                var re1= document.getElementsByName("canton");
                var actividad1=<HTMLInputElement>re1[0]["value"];

                if(actividad1+"" == 'empty'){
                  alert("Seleccione un cantón");
                  this.spinner.hide();
                  return;
                } else {
                  
                  if(this.optionsModel.length<=0){
                    alert("Seleccione un sector");
                    this.spinner.hide();
                    return;
                  } else {
                    var re3= document.getElementsByName("cantidad_actividades");
                    var actividad3=<HTMLInputElement>re3[0]["value"];
                    
                    if(Number(actividad3) == 0){
                      alert("NO ha seccionado el número actividades");
                      this.spinner.hide();
                      return;
                    }
                  }
                }
            }
            
            var cant=document.getElementsByName("cantidad_actividades");
            var cantidad_actividades=<HTMLInputElement>cant[0]["value"];
            
            this.cantidad.subscribe(
              msj=>{
                //alert(msj.length);
                if(msj.length<=0){
                  alert("seleccione actividades a distribuir");
                  this.spinner.hide();
                  return;
                }
              //this.loading=true;
              //this.spinner.show();
               msj.forEach(element => {
                 array_actividades[cont]=element["id_act"];
                 cont++;
               });

               let dataBuild={
                  'array_actividades':array_actividades,
                  'array_tecnicos':array_tecnicos,
                  'actividad':actividad,
                  'cantidad_actividades':cantidad_actividades
                }
                
               this.tecnicoService.buildTecnicoByTask(dataBuild).subscribe(
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
                      this.spinner.hide();
                    }else if(result==1){
                      alert("El  número de actividades no puede ser igual o menor a cero  ");
                      this.spinner.hide();
                    }else{
                      alert("No se asigno las actividades  ");
                      this.spinner.hide();
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
      
      for(var i=0; i < this.listTecnicosSeleccionados.length; i++){ 
          array_tecnicos[cont_array_tecn]=this.listTecnicosSeleccionados[i].value;
          cont_tecnicos++;
          cont_array_tecn++;
      }
      
    }

  mostrarDistribucion(){
    console.log("MOSTRAR LA DISTRIBUCION ===================");
    this.distribucion = this.tecnicoService.showDistribucion();
    this.distribucion.subscribe(res =>{
      console.log(res);
    });
  }

  eliminarAsignacion(id_tecn, sector, cantidad, tipoAct){
    this.spinner.show();
    //console.log("ELIMINAR LA DISTRIBUCION ===================");
    this.distribucionDelete = this.tecnicoService.deleteDistribucion(id_tecn, sector, cantidad, tipoAct);
    this.distribucionDelete.subscribe(res =>{
      //console.log(res);
      if(res == true){
        //alert("Asignación eliminada correctamente");
        this.reloadComponent();
        this.spinner.hide();
        //location.reload(true);
      } else {
        
        alert("Error al eliminar la asignación");
        this.reloadComponent();
        this.spinner.hide();
      }
    });
  }

}
