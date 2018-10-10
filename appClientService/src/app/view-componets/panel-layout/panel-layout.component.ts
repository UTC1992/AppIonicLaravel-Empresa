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
    
  ordenes:Observable<Orden[]>; 
  public view_table:boolean;
  tecnicos:Observable<Tecnico[]>;
  public view_data_empty:boolean;
  constructor(private ordenService:OrdenService, private tecnicoService:TecnicoService,private excelService:ExcelServiceService) 
  {
    this.view_table=false;
    this.view_data_empty=false;
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


}
