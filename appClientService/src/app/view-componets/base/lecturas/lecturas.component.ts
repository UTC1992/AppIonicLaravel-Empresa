import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import { Observable, from } from 'rxjs';
import { LecturasService } from '../../../services/lecturas.service';
import { Filtro} from '../../../models/filtro';
import { DataFilter } from "../../../models/data-filter";
import { Tecnico } from "../../../models/tecnico";
import { TecnicoService } from "../../../services/tecnico.service";

@Component({
  selector: 'app-lecturas',
  templateUrl: './lecturas.component.html',
  styleUrls: ['./lecturas.component.css']
})
export class LecturasComponent implements OnInit {

  form: FormGroup;
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

  datosAsignacion:Observable<any>;
  @ViewChild('fileInput') fileInput: ElementRef;

  constructor(private fb: FormBuilder,
    private lecturasService:LecturasService,
    private tecnicoService:TecnicoService) { 
    this.createForm();
  }

  ngOnInit() {
    this.getFiltesFields();
    this.getFirstFilterFields();
    this.getTenicosLecturas();
    this.datosAsignacionTecnicos();
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
   * 
   */
  onSubmit(){
    if(this.form.get('archivo').value==null || this.form.get('archivo').value==""){
      alert("Seleccione un archivo");
      return;
    }
    const formModel = this.prepareSave();
    this.lecturasService.uploadFile(formModel).subscribe(
      result=>{
        console.log(result);
        alert(result);
        this.clearFile();
      }
    );
    
   
  }

  /**
   * 
   */
  clearFile() {
    this.form.get('archivo').setValue(null);
    this.fileInput.nativeElement.value = '';
  }

/**
 * 
 * @param event 
 */
  onFileChange(event) {
    if(event.target.files.length > 0) {
      let file = event.target.files[0];
      this.form.get('archivo').setValue(file);
    }
  }

  /**
   * 
   */
  createForm() {
    this.form = this.fb.group({
      archivo: null
    });
  }

  /**
   * 
   */
  prepareSave(): any {
    let input = new FormData();
    input.append('file', this.form.get('archivo').value);
    return input;
  }

  /**
   * mapea servcio de filtros
   */
  getFiltesFields(){
    this.lecturasService.getFilterFields().subscribe(
      result=>{
        this.filtrosLabel=result;
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
    //console.log(tecnico);
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
        //console.log(result);
        window.location.reload();
      }
    );    
    //console.log(this.idsLecturas);
  }

  datosAsignacionTecnicos(){
    this.lecturasService.OrdentrabajoTecnicosLecturas().subscribe(
      result=>{
        this.datosAsignacion=result;
        //console.log(this.datosAsignacion);
      }
    );
  }
}
