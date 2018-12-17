import { Component, OnInit,ElementRef, ViewChild } from '@angular/core';
import { Orden } from '../../models/orden';
import { InjectableAnimationEngine } from '@angular/platform-browser/animations/src/providers';
import { OrdenService } from '../../services/orden.service';
import { TecnicoService } from '../../services/tecnico.service';
import { ExcelServiceService } from '../../services/excel-service.service';
import { Tecnico } from '../../models/tecnico';
import { Observable } from 'rxjs';

import { NgxSpinnerService } from 'ngx-spinner';
import { TableRecmanualComponent } from '../table-recmanual/table-recmanual.component';

@Component({
  selector: 'app-panel-layout',
  templateUrl: './panel-layout.component.html',
  styleUrls: ['./panel-layout.component.css']
})

export class PanelLayoutComponent implements OnInit {
  url_export='http://pruebas.tiendanaturalecuador.online/api/export';
  //url_export='http://gestiondcyk.tecnosolutionscorp.com/api/export';
  //url_export='http://localhost:8000/api/export';
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

  constructor(private ordenService:OrdenService, 
              private tecnicoService:TecnicoService,
              private excelService:ExcelServiceService,
              private spinner: NgxSpinnerService) 
  {
    this.view_table=false;
    this.view_data_empty=false;
   }

  ngOnInit() {  
    this.tecnicos=this.tecnicoService.getAllTecnicos();
    this.recmanualesExcel = false;
  }

  verActividaes(){
    //ocultar las reconexiones manuales
    this.tablaRecManual.ocultarRecManuales();
    this.tablaRecManual.ocultarEmptyRecManuales();
    //bloquear descarga de recmanual
    this.recmanualesExcel = false;

    var result = document.getElementById("fechaReporte");
    var fecha=<HTMLInputElement>result["value"];
    var tecnico = <HTMLInputElement>document.getElementById("tecnicos_select")["value"];
    var actividad = <HTMLInputElement>document.getElementById("actividades_select")["value"];
    var estado = <HTMLInputElement>document.getElementById("estado_select")["value"];
    if(fecha){
      
      this.ordenes=this.ordenService.getActivitiesToDay(fecha,tecnico,actividad,estado);
      this.ordenes.subscribe(
        data=>{
          //console.log(data);
          if(data.length>0){
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
      alert("Seleccione una fecha");
    }
    
  }
  exportarExcelActividades(){
    var date = document.getElementById("fechaReporte")["value"]+"";
    var vector = date.split("-");
    var fecha=vector[2]+"-"+vector[1]+"-"+vector[0]
    if(this.ordenes != null && this.view_table==true){
      let datos = Array();
      this.ordenes.subscribe(
        data=>{
          for (var i = 0; i < data.length; ++i) {
            datos.push({
                      ACTIVIDAD:    data[i]['n9cono'],
                      CUENTA:       data[i]['n9cocu'],
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

          this.excelService.exportAsExcelFile(datos,fecha+'_Actividades');
        });
    }
    
    if(this.recmanualesExcel==true){
      this.tablaRecManual.exportarExcelRecManual(fecha);
    }
      
  }


  //consolidar actividades diarias
  consolodarActividades(){
    var date = document.getElementsByName("fecha")[0]["value"];
    //console.log("fecha de consolidado ==> " + date);
    if(date!=""){
      this.spinner.show();
      //this.loading=true;
      this.ordenService.consolidarActividades(date).subscribe(
        result=>{
          if(result){
            var id_emp2=localStorage.getItem("id_emp");
            this.fecha_consolidado=date;
            this.id_emp=id_emp2;
            this.exportable=true;
            this.spinner.hide();
            //this.loading = false;
            alert("Actividades Consolidadas Correctamente");
          }else{
            alert(result);
            this.spinner.hide();
          }
        }
      );
    }else{
      alert("Seleccione una fecha");
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
    var id_emp=localStorage.getItem("id_emp");
    var date = document.getElementsByName("fecha")[0]["value"];
    if(date==""){
      alert('seleccione una fecha');
      return;
    }
    if(id_emp!=""){
      alert('Ocurrio un error!');
      return;
    }

    this.url_export=this.url_export+'/'+date+'/'+id_emp;
  }

  verRecManual(){
    var result = document.getElementById("fechaReporte");
    var fecha=<HTMLInputElement>result["value"];
    var tecnico = <HTMLInputElement>document.getElementById("tecnicos_select")["value"];
    if(fecha){
      let dataRecManual={
          'fecha':fecha,
          'id_tecn':tecnico,
          }
      this.view_table=false;
      this.view_data_empty=false;
      this.recmanualesExcel=true;
      this.tablaRecManual.cargarDatos(dataRecManual);
      
    }else{
      alert("Seleccione una fecha");
    }
    
  }


}
