import { Component, OnInit } from '@angular/core';
import { Orden } from '../../models/orden';
import { InjectableAnimationEngine } from '@angular/platform-browser/animations/src/providers';
import { OrdenService } from '../../services/orden.service';
import { TecnicoService } from '../../services/tecnico.service';
import { ExcelServiceService } from '../../services/excel-service.service';
import { Tecnico } from '../../models/tecnico';
import { Observable } from 'rxjs';

@Component({
  selector: 'app-panel-layout',
  templateUrl: './panel-layout.component.html',
  styleUrls: ['./panel-layout.component.css']
})

export class PanelLayoutComponent implements OnInit {
  
  url_export='http://localhost:8000/api/export';
  fecha_consolidado='';
  loading:boolean;
  exportable:boolean;
  ordenes:Observable<Orden[]>; 
  public view_table:boolean;
  tecnicos:Observable<Tecnico[]>;
  public view_data_empty:boolean;
  ordenesConsolidados:Observable<Orden[]>;

  constructor(private ordenService:OrdenService, private tecnicoService:TecnicoService,private excelService:ExcelServiceService) 
  {
    this.view_table=false;
    this.view_data_empty=false;
    this.loading=false;
    this.exportable=false;
   }

  ngOnInit() {  
    this.tecnicos=this.tecnicoService.getAllTecnicos();
  }

  verActividaes(){
    var result = document.getElementById("fecha");
    var fecha=<HTMLInputElement>result["value"];
    var tecnico = <HTMLInputElement>document.getElementById("tecnicos_select")["value"];
    var actividad = <HTMLInputElement>document.getElementById("actividades_select")["value"];
    var estado = <HTMLInputElement>document.getElementById("estado_select")["value"];
    if(fecha){
      
      this.ordenes=this.ordenService.getActivitiesToDay(fecha,tecnico,actividad,estado);
      this.ordenes.subscribe(
        data=>{
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
          this.excelService.exportAsExcelFile(data,'Actividades');
        }
      );
      
  }
  //consolidar actividades diarias
  consolodarActividades(){
    var date = document.getElementsByName("fecha")[0]["value"];
    if(date!=""){
      this.loading=true;
      this.ordenService.consolidarActividades(date).subscribe(
        result=>{
          if(result){
            this.exportable=true;
            this.loading=false;
			this.fecha_consolidado=date;
            alert("Actividades Consolidadas Correctamente");
          }else{
            alert(result);
          }
        }
      );
    }else{
	  this.exportable=false; 
      alert("Seleccione una fecha");
      return;
    }

    
  }

  //exportar excel 
  exportarConsolidado(){
    var date = document.getElementsByName("fecha")[0]["value"];
    if(date==""){
      alert('seleccione una fecha');
      return;
    }
    this.url_export=this.url_export+'/'+date;
  }


}
