import { Component, OnInit,ElementRef, ViewChild } from '@angular/core';
import { Orden } from '../../../models/orden';
import { InjectableAnimationEngine } from '@angular/platform-browser/animations/src/providers';
import { OrdenService } from '../../../services/orden.service';
import { TecnicoService } from '../../../services/tecnico.service';
import { ExcelServiceService } from '../../../services/excel-service.service';
import { Tecnico } from '../../../models/tecnico';
import { Observable } from 'rxjs';

import {FormBuilder, FormGroup, Validators, FormControl} from '@angular/forms';

import { TableRecmanualComponent } from '../table-recmanual/table-recmanual.component';

import {MomentDateAdapter} from '@angular/material-moment-adapter';
import {DateAdapter, MAT_DATE_FORMATS, MAT_DATE_LOCALE} from '@angular/material/core';
import * as moment from 'moment';

import {MatTableDataSource, MatPaginator} from '@angular/material';
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
  selector: 'app-panel-layout',
  templateUrl: './panel-layout.component.html',
  styleUrls: ['./panel-layout.component.css'],
  providers: [
    {provide: DateAdapter, useClass: MomentDateAdapter, deps: [MAT_DATE_LOCALE]},

    {provide: MAT_DATE_FORMATS, useValue: MY_FORMATS},
  ],
})

export class PanelLayoutComponent implements OnInit {
  //url_export='http://pruebas.tiendanaturalecuador.online/api/export';
  //url_export='http://gestiondcyk.tecnosolutionscorp.com/api/export';
  url_export='http://localhost:8000/api/export';
  //url_export='http://pruebascortes.tecnosolutionscorp.com/api/export';
  
  @ViewChild(TableRecmanualComponent) tablaRecManual: TableRecmanualComponent;

  recmanualesExcel: boolean;

  today:Date;
  fecha_consolidado='';
  loading:boolean;
  exportable:boolean;
  ordenes:Observable<Orden[]>; 
  public view_table:boolean;
  tecnicos:Observable<Tecnico[]>;
  public view_data_empty:boolean;
  id_emp='';
  fecha = new Date();

  //fecha de filtro
  fechaBuscar: string = null;
  tecnicoBuscar: string = 'empty';
  actividadBuscar: string = 'empty';
  estadoBuscar: string = 'empty';

  date = new FormControl(
    'date', [
      Validators.required
    ]
  );

  displayedColumns: string[] = ['index', 'tecnico','actividad', 
  'cuenta', 'canton', 'sector', 'medidor', 'lectura',
  'usuario', 'latitud', 'longitud', 'hora', 'novedad',
  'estadoAct', 'estadoFinal'];
  dataSource = new MatTableDataSource();
  @ViewChild(MatPaginator) paginator: MatPaginator;

  constructor(private ordenService:OrdenService, 
              private tecnicoService:TecnicoService,
              private excelService:ExcelServiceService,
              private adapter: DateAdapter<any>,
              private fb: FormBuilder
  ){
    this.view_table=false;
    this.view_data_empty=false;
   }

  ngOnInit() {
    this.tecnicos=this.tecnicoService.getTecnicosCortes();
    this.recmanualesExcel = false;
    this.french();
    this.inicializarTabladeDatos();
  }

  inicializarTabladeDatos(){
    this.ordenes=this.ordenService.getActivitiesToDay("0000-00-00","empty","empty","empty");
      this.ordenes.subscribe(
        data=>{
          console.log(data);
            this.dataSource = new MatTableDataSource(data);
            this.dataSource.paginator = this.paginator;
        }
      );
  }

  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }

  french() {
    this.adapter.setLocale('es');
  }

  getFecha(pickerInput: string): void {
    this.fechaBuscar = pickerInput;
    //console.log(this.fechaBuscar);
  }

  getErrorMessage(pickerInput: string): string {
    if (!pickerInput || pickerInput === '' ) {
      return 'Please choose a date.';
    }
    return pickerInput;
  }



  verActividaes(){
    //ocultar las reconexiones manuales
    this.tablaRecManual.ocultarRecManuales();
    this.tablaRecManual.ocultarEmptyRecManuales();
    //bloquear descarga de recmanual
    this.recmanualesExcel = false;

    if(this.fechaBuscar != null){
      var vectorFecha = this.fechaBuscar.split('-');
      var fecha=vectorFecha[2]+"-"+vectorFecha[1]+"-"+vectorFecha[0];
      var tecnico = this.tecnicoBuscar;
      var actividad = this.actividadBuscar;
      var estado = this.estadoBuscar;
      console.log(fecha);

      this.ordenes=this.ordenService.getActivitiesToDay(fecha,tecnico,actividad,estado);
      this.ordenes.subscribe(
        data=>{
          console.log(data);
          if(data.length>0){
            this.dataSource.data = data;
            this.dataSource.paginator = this.paginator;
            this.view_table=true;
            this.view_data_empty=false;
          }else{
            this.view_table=false;
            this.view_data_empty=true;
          }
        }
      );
    }else{
      this.view_table=false;
      this.view_data_empty=false;
      this.showAlert("Alerta!", "Debe elegir una fecha para mostrar los datos.", "warning");
    }
    
  }
  exportarExcelActividades(){
    if(this.fechaBuscar != null){
      console.log(this.fechaBuscar);
      var date = this.fechaBuscar;
      var vector = date.split("-");
      var fecha=vector[2]+"-"+vector[1]+"-"+vector[0];
      if(this.ordenes != null && this.view_table==true){
        let datos = Array();
        this.ordenes.subscribe(
          data=>{
            for (var i = 0; i < data.length; ++i) {
              datos.push({
                        TECNICO:      data[i]['nombres']+" "+data[i]['apellidos'],
                        ACTIVIDAD:    data[i]['n9cono'],
                        CUENTA:       data[i]['n9cocu'],
                        CANTON:       data[i]['n9coag'],
                        SECTOR:       data[i]['n9cose'],
                        MEDIDOR:      data[i]['n9meco'],
                        LECTURA:      data[i]['n9leco'],
                        CLIENTE:      data[i]['n9nomb'],
                        HORATAREA:    data[i]['hora'],
                        NOVEDADES:    data[i]['observacionFin'],
                        ESTADO:       data[i]['referencia'],
                        CUCOON:       data[i]['cucoon'],
                        CUCOOE:       data[i]['cucooe']
                      });
            }
            console.log(data);
            this.excelService.exportAsExcelFile(datos,fecha+'_Actividades');
          });
      } 

      if(this.recmanualesExcel==true){
        this.tablaRecManual.exportarExcelRecManual(fecha);
      } else {
        this.showAlert("Información!", 
        "Debe mostrar los datos para descargarlos.", "info");
      }
    } else {
      this.showAlert("Alerta!", "Debe elegir una fecha y mostrar los datos para descargarlos.", "warning");
    }
    
      
  }


  //consolidar actividades diarias
  consolodarActividades(){
    var date = this.fechaBuscar;
    //console.log("fecha de consolidado ==> " + date);
    if(date!=""){
      //this.loading=true;
      this.ordenService.consolidarActividades(date).subscribe(
        result=>{
          if(result){
            var id_emp2=sessionStorage.getItem("id_emp");
            this.fecha_consolidado=date;
            this.id_emp=id_emp2;
            this.exportable=true;
            //this.loading = false;
            this.showAlert("Éxito!","Actividades Consolidadas Correctamente", "success");
          }else{
            this.showAlert("Alert!",
            "Debe finalziar o eliminar todas las asignaciones pendientes para consolidar los datos.",
            "success");
            
          }
        }
      );
    }else{
      this.showAlert("Alerta!", "Debe elegir una fecha.", "warning");
      return;
    }
  }

  //exportar excel 
  /*exportarConsolidado(){
  //this.loading=true;
  this.spinner.show();
    var date = document.getElementsByName("fecha")[0]["value"]+"";
    var vector = date.split("-");
    var nombre_consolidado=vector[2]+"-"+vector[1]+"-"+vector[0]+"_Consolidado"
    //console.log("fecha de consolidado ==> " + nombre_consolidado);
    this.ordenService.obtenerCosolidadosDelDia(date).subscribe(
      result=>{
        //this.loading = false;
        this.spinner.hide();
        this.excelService.exportAsExcelFile(result,nombre_consolidado);
        document.getElementsByName("fecha")[0]["value"] = "";
        this.exportable=false;
      });
  }
  */


  //exportar excel 
  exportarConsolidado(){
    var id_emp=sessionStorage.getItem("id_emp");
    var date = this.fechaBuscar;
    if(date==""){
      this.showAlert("Alerta!", "Debe elegir una fecha.", "warning");
      return;
    }
    if(id_emp!=""){
      //alert('Ocurrio un error!');
      this.showAlert("Alerta!", "Ocurrio un error al exportar.", "warning");
      return;
    }

    this.url_export=this.url_export+'/'+date+'/'+id_emp;
  }

  verRecManual(){
    if(this.fechaBuscar != null){
      var date = this.fechaBuscar;
      var vector = date.split("-");
      var fecha=vector[2]+"-"+vector[1]+"-"+vector[0];
      var tecnico = this.tecnicoBuscar;
      if(fecha){
        let dataRecManual={
            'fecha':fecha,
            'id_tecn':tecnico,
            }
        this.view_table=false;
        this.view_data_empty=false;
        this.recmanualesExcel=true;
        this.tablaRecManual.cargarDatos(dataRecManual);
        console.log(dataRecManual);
    }

    }else{
      this.showAlert("Alerta!", "Debe elegir una fecha para mostrar los datos.", "warning");
    }
    
  }

  showAlert(title, text, type){
    Swal.fire({
      title: title,
      text: text,
      type: type,
      allowOutsideClick: false
    });
  }

}
