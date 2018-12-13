import { Component, OnInit } from '@angular/core';
import { Orden } from '../../models/orden';
import { InjectableAnimationEngine } from '@angular/platform-browser/animations/src/providers';
import { OrdenService } from '../../services/orden.service';
import { TecnicoService } from '../../services/tecnico.service';
import { ExcelServiceService } from '../../services/excel-service.service';
import { Tecnico } from '../../models/tecnico';
import { Observable } from 'rxjs';

import { NgxSpinnerService } from 'ngx-spinner';

@Component({
  selector: 'app-panel-layout',
  templateUrl: './panel-layout.component.html',
  styleUrls: ['./panel-layout.component.css']
})

export class PanelLayoutComponent implements OnInit {
  //url_export='http://pruebas.tiendanaturalecuador.online/api/export';
  //url_export='http://gestiondcyk.tecnosolutionscorp.com/api/export';
  url_export='http://localhost:8000/api/export';
  //url_export='http://pruebascortes.tecnosolutionscorp.com/api/export';
  
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
  }

  verActividaes(){
    var result = document.getElementById("fechaReporte");
    var fecha=<HTMLInputElement>result["value"];
    var tecnico = <HTMLInputElement>document.getElementById("tecnicos_select")["value"];
    var actividad = <HTMLInputElement>document.getElementById("actividades_select")["value"];
    var estado = <HTMLInputElement>document.getElementById("estado_select")["value"];
    if(fecha){
      
      this.ordenes=this.ordenService.getActivitiesToDay(fecha,tecnico,actividad,estado);
      this.ordenes.subscribe(
        data=>{
          console.log(data);
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
  exportarExcel(){
      this.ordenes.subscribe(
        data=>{
          console.log(data);
          let fechaActual = this.fecha.getDate()+"-"+(this.fecha.getMonth() +1)+"-"+this.fecha.getFullYear();
          this.excelService.exportAsExcelFile(data, fechaActual+'-CONSOLIDADO');
        }
      );
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


}
