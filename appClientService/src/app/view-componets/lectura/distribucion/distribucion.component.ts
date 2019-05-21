import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import { LecturasService } from '../../../services-lecturas/lecturas.service';
import { Filtro} from '../../../models/filtro';
import { DataFilter } from "../../../models/data-filter";
import { Tecnico } from "../../../models/tecnico";
import { TecnicoService } from "../../../services/tecnico.service";

@Component({
  selector: 'app-distribucion',
  templateUrl: './distribucion.component.html',
  styleUrls: ['./distribucion.component.css']
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
  listTecnicosSeleccionados: any[];

  constructor(
    private fb: FormBuilder,
    private lecturasService:LecturasService,
    private tecnicoService:TecnicoService
  ) { }

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
    let valor= $event.target.value;
    if(valor=="empty"){
      alert("selecciona algo");
      return;
    }

    if(order==1){
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
    let tecnico=this.tecnicoSeleccionado = e.option.value;
    //this.listTecnicosSeleccionados = list;
    console.log(tecnico);
  }

  /** 
   * asignar rura a técnico seleccinado
   */

  asignarRutaTecnico(){

    let data={
      'lecturas':this.idsLecturas,
      'idTecnico':this.tecnicoSeleccionado,
    };
    this.lecturasService.distribuirRutasTecnico(data).subscribe(
      result=>{
        console.log(result);
      }
    );    
    //console.log(this.idsLecturas);
  }

}
