import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import {FormBuilder, FormGroup, Validators, FormControl} from "@angular/forms";
import { LecturasService } from '../../../services-lecturas/lecturas.service';
import { Filtro} from '../../../modelos-lec/filtro';
import { DataFilter } from "../../../models/data-filter";
import { Tecnico } from "../../../models/tecnico";
import { TecnicoService } from "../../../services/tecnico.service";

import {MomentDateAdapter} from '@angular/material-moment-adapter';
import {DateAdapter, MAT_DATE_FORMATS, MAT_DATE_LOCALE} from '@angular/material/core';

import Swal from 'sweetalert2';
import { DataRowOutlet } from '@angular/cdk/table';
import { Runner } from 'protractor';

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

  cantidad_lecturas:number = 0;

  idsLecturas:Filtro[]=[];
  tecnicosLecturas:Tecnico[]=[];

  tecnicoSeleccionado: string;
  listTecnicosSeleccionados: any[] = [];

  //validar seleccion
  agenciaValidar: any = null;
  sectorValidar: any = null;
  rutaValidar: any = null;
  
  //rutas
  rutasObtenidas: Filtro[] = null;
  agenciaElegida: string = null;
  sectorElegido: string = null;
  rutaElegida: string = null;
  rutasList: Filtro[] = [];
  formRutas = new FormControl();

  //mostrar filtros
  mostrarFiltro2: boolean = false;
  mostrarFiltro3: boolean = false;
  mostrarCantidad: boolean = false;

  constructor(
    private fb: FormBuilder,
    private lecturasService:LecturasService,
    private tecnicoService:TecnicoService,
    private dateAdapter: DateAdapter<Date>,
  ) {
    this.dateAdapter.setLocale('es'); 
   }

  ngOnInit() {
    this.getTenicosLecturas();
    this.obtenerRutas();
  }

  obtenerRutas(){
    this.lecturasService.getRutasAll().subscribe(response => {
      console.log(response);
      this.rutasObtenidas = response;
      for (let i = 0; i < response.length; i++) {
        var valor = this.primerFiltro.find(x => x.agencia == response[i].agencia);
        if (!valor) {
          this.primerFiltro.push(response[i])
          console.log(this.primerFiltro);
        }
      }
    });
  }

  /**
   * obtiene tecnicos desde servicio técnicos
   */
  getTenicosLecturas(){
    this.tecnicoService.getTecnicosLecturasSinAsignar().subscribe(
      result=>{
        this.tecnicosLecturas=result;
        //console.log("tecnicos"+this.tecnicosLecturas);
      }
    );
  }

  inicializarFiltros(){
    this.segundoFiltro = [];
    this.tercerFiltro = [];
    this.rutasList = [];
    this.agenciaElegida = null;
    this.sectorElegido = null;
    this.rutaElegida = null;
    this.cantidad_lecturas = 0;
    this.formRutas = new FormControl();
    this.mostrarFiltro2 = false;
    this.mostrarFiltro3 = false;
    this.mostrarCantidad = false;
  }


  //obtener sectores
  getSectores($event){
    this.inicializarFiltros();
    
    console.log($event.value);
    let agencia = $event.value;
    this.agenciaElegida = agencia;
    for (let i = 0; i < this.rutasObtenidas.length; i++) {
      var valor = this.rutasObtenidas.find(x => x.agencia == agencia);
      if (valor) {
        let valor2 = this.segundoFiltro.find(x => x.sector == this.rutasObtenidas[i].sector);
        if(!valor2){
          this.segundoFiltro.push(this.rutasObtenidas[i]);
          console.log(this.segundoFiltro);
          this.mostrarFiltro2 = true;
        }
        
      }
    }

  }

  //obtener rutas
  getRutas($event){
    //inicializar los variables globales
    this.tercerFiltro = [];
    this.rutasList = [];
    this.rutaElegida = null;
    this.cantidad_lecturas = 0;
    this.mostrarFiltro3 = false;
    this.mostrarCantidad = false;
    this.formRutas = new FormControl();

    console.log($event.value);
    let sector = $event.value;
    this.sectorElegido = sector;
    for (let i = 0; i < this.rutasObtenidas.length; i++) {
      if (this.rutasObtenidas[i].sector == sector && this.rutasObtenidas[i].agencia == this.agenciaElegida) {
        let valor3 = this.tercerFiltro.find(x => x.ruta == this.rutasObtenidas[i].ruta);
        if(!valor3){
          this.tercerFiltro.push(this.rutasObtenidas[i]);
          console.log(this.tercerFiltro);
          this.mostrarFiltro3 = true;
        }
        
      }
    }

  }

  getCantidad(dato : any){
    this.mostrarCantidad = true;
    console.log(dato);
    let ruta = dato;
    this.rutaElegida = ruta;
    //se obtiene el valor de cantidad
    let cantidad = this.tercerFiltro.find(x => x.ruta == ruta).cantidad;

    let valor3 = this.rutasList.find(x => x.ruta == ruta);
    if(valor3){
      let vectorAux: Filtro[] = [];
      for (let j = 0; j < this.rutasList.length; j++) {
        if(this.rutasList[j].ruta != valor3.ruta){
          vectorAux.push(this.rutasList[j]);
        } 
      }
      this.rutasList = [];
      this.rutasList = vectorAux;
      this.cantidad_lecturas -= cantidad;
      console.log(this.rutasList);
    } else {
      for (let i = 0; i < this.rutasObtenidas.length; i++) {
        if (this.rutasObtenidas[i].sector == this.sectorElegido 
          && this.rutasObtenidas[i].agencia == this.agenciaElegida
          && this.rutasObtenidas[i].ruta == ruta
          ) {
            
            this.rutasList.push(this.rutasObtenidas[i]);
            this.cantidad_lecturas += this.rutasObtenidas[i].cantidad;
            console.log(this.rutasList);
          
        }
      }
    }
  }

  /**
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
    
    if(this.agenciaElegida == null){
      this.showAlert('Alerta!',"Seleccione una agencia",'warning');
    } else if(this.sectorElegido == null){
      this.showAlert('Alerta!',"Seleccione un sector",'warning');
    } else if(this.rutaElegida == null){
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
