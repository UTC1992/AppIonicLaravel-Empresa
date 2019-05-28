import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import { LecturasService } from '../../../services-lecturas/lecturas.service';
import { Filtro} from '../../../models/filtro';
import { DataFilter } from "../../../models/data-filter";
import { Tecnico } from "../../../models/tecnico";
import { TecnicoService } from "../../../services/tecnico.service";

import {MomentDateAdapter} from '@angular/material-moment-adapter';
import {DateAdapter, MAT_DATE_FORMATS, MAT_DATE_LOCALE} from '@angular/material/core';

import Swal from 'sweetalert2';

export const MY_FORMATS = {
  parse: {
    dateInput: 'DD-MM-YYYY',
  },
  display: {
    dateInput: 'DD-MM-YYYY',
    monthYearLabel: 'MMM YYYY',
    dateA11yLabel: 'DD-MM-YYYY',
    monthYearA11yLabel: 'MMMM YYYY',
  },
};


@Component({
  selector: 'app-distribucion',
  templateUrl: './distribucion.component.html',
  styleUrls: ['./distribucion.component.css'],
  providers: [
    {provide: DateAdapter, useClass: MomentDateAdapter, deps: [MAT_DATE_LOCALE]},

    {provide: MAT_DATE_FORMATS, useValue: MY_FORMATS},
  ],
})
export class DistribucionComponent implements OnInit {

  filtrosLabel: Filtro[]=[];
  primerFiltro:Filtro[]=[];
  segundoFiltro:Filtro[]=[];
  tercerFiltro:Filtro[]=[];
  dataFilter:DataFilter= new DataFilter();
  dataCount:boolean=false;
  cantidad_lecturas:number;
  idsLecturas:Filtro[]=[];
  tecnicosLecturas:Tecnico[]=[];

  tecnicoSeleccionado: string;
  listTecnicosSeleccionados: any[] = [];

  //validar seleccion
  agenciaValidar: any = null;
  sectorValidar: any = null;
  rutaValidar: any = null;
  

  constructor(
    private fb: FormBuilder,
    private lecturasService:LecturasService,
    private tecnicoService:TecnicoService,
    private dateAdapter: DateAdapter<Date>,
  ) {
    this.dateAdapter.setLocale('es'); 
   }

  ngOnInit() {
    this.getFiltesFields();
    this.getFirstFilterFields();
    this.getTenicosLecturas();
  }

  /**
   * obtiene tecnicos desde servicio técnicos
   */
  getTenicosLecturas(){
    this.tecnicoService.getTecnicosLecturasSinAsignar().subscribe(
      result=>{
        this.tecnicosLecturas=result;
        console.log("tecnicos"+this.tecnicosLecturas);
      }
    );
  }
  

  /**
   * mapea servcio de filtros
   */
  getFiltesFields(){
    this.lecturasService.getFilterFields().subscribe(
      result=>{
        this.filtrosLabel=result;
        console.log("Filtros ==> ");
        console.log(this.filtrosLabel);
      }
    );
  }

  /**
   * 
   */
  getFirstFilterFields(){
    this.dataCount=false;
    this.lecturasService.getFirstFilterFields().subscribe(
      result=>{

        this.primerFiltro=result;
        console.log(this.primerFiltro[0]);
      }
    );
  }

  /**
   * 
   */
  getDataFilter($event,order){
    console.log(order);
    let valor= $event.value;
    if(valor=="empty" && order == 1){
      this.showAlert('Alerta!',"Seleccione una agencia",'warning');
      this.agenciaValidar = null;
      return;
    }
    if(valor=="empty" && order == 2){
      this.showAlert('Alerta!',"Seleccione un sector",'warning');
      this.sectorValidar = null;
      return;
    }
    if(valor=="empty" && order == 3){
      this.showAlert('Alerta!',"Seleccione una ruta",'warning');
      this.rutaValidar = null;
      return;
    }

    if(order==1){
    this.agenciaValidar = true;

    this.idsLecturas=[];
    this.dataCount=false;
    this.dataFilter=new DataFilter();
    
    this.segundoFiltro=[];
    this.tercerFiltro=[];


    let data={
        'whereData':{
          'agencia':valor
        } 
    };
    this.dataFilter.whereData[0]=null;
    this.dataFilter.whereData[1]=null;
    this.dataFilter.whereData[2]=null;
    this.cantidad_lecturas=0;

    this.dataFilter.whereData[0]=data;
    this.dataFilter.select="sector";
    this.lecturasService.getDataFilter(this.dataFilter).subscribe(
      result=>{
        this.segundoFiltro=result;
        console.log(result);
      }
    ); 
    }

    if(order==2){
      this.sectorValidar = true;

      this.dataCount=false;
      let data2={
        'whereData':{
          'sector':valor
        } 
      };
      this.dataFilter.whereData[1]=null;
      this.dataFilter.whereData[2]=null;
      this.cantidad_lecturas=0;

      this.dataFilter.whereData[1]=data2;
      this.dataFilter.select="ruta";
      this.tercerFiltro=[];
      this.lecturasService.getDataFilter(this.dataFilter).subscribe(
        result=>{
          this.tercerFiltro=result;
          console.log(result);
        }
      ); 
    }
    if(order==3){
      this.rutaValidar = true;
      let data={
        'whereData':{
          'ruta':valor
        } 
      };
      this.dataFilter.whereData[2]=null;
      this.dataFilter.whereData[2]=data;
     this.cantidad_lecturas=0;
      
      this.lecturasService.getDataDistribution(this.dataFilter).subscribe(
        result=>{
          this.idsLecturas=result;
          this.dataCount=true;
          this.cantidad_lecturas=result.length;
          console.log(result);
        }
      );
    }

  }


  /**
   * 
   * @param e 
   * @param list 
   */
  onSelection(e, list){
    this.tecnicoSeleccionado = e.option.value;
    this.listTecnicosSeleccionados = list;
    console.log(this.listTecnicosSeleccionados);
  }

  /** 
   * asignar rura a técnico seleccinado
   */

  asignarRutaTecnico(){
    
    if(this.agenciaValidar == null){
      this.showAlert('Alerta!',"Seleccione una agencia",'warning');
    } else if(this.sectorValidar == null){
      this.showAlert('Alerta!',"Seleccione un sector",'warning');
    } else if(this.rutaValidar == null){
      this.showAlert('Alerta!',"Seleccione una ruta",'warning');
    } else if(this.listTecnicosSeleccionados.length != 1){
      this.showAlert('Alerta!',"Seleccione un solo técnico",'warning');
    }
    if(this.agenciaValidar && this.sectorValidar 
      && this.rutaValidar && this.listTecnicosSeleccionados.length == 1){
      this.showCargando();
      console.log(this.listTecnicosSeleccionados[0].value);
      let data={
        'lecturas':this.idsLecturas,
        'idTecnico':this.listTecnicosSeleccionados[0].value,
      };
      this.lecturasService.distribuirRutasTecnico(data).subscribe(
        result=>{
          console.log(result);
          if(result){
            //this.getTenicosLecturas();
            this.showAlert('Éxito', 'Ruta asignada correctamente', 'success');
          } else {
            this.showAlert('Alerta!', 'No se pudo asignar la ruta', 'warning');
          }
        }, error => {
          console.log(error);
          Swal.close();
        }
      );
    }
        
    //console.log(this.idsLecturas);
  }

  showAlert(title, text, type){
    Swal.fire({
      title: title,
      text: text,
      type: type,
      allowOutsideClick: false
    });
  }

  showCargando(){
    let swal = Swal;
    swal.fire({
      title: 'Espere por favor...',
      showCloseButton: false,
      showCancelButton: false,
      showConfirmButton: false,
      allowOutsideClick: false,
      allowEscapeKey:false,
      onOpen: () => {
        Swal.showLoading();
      }
    });
  }

}
