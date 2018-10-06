import { Component, OnInit,Input } from '@angular/core';
import { Orden } from '../../models/orden';
import { InjectableAnimationEngine } from '@angular/platform-browser/animations/src/providers';
import { OrdenService } from '../../services/orden.service';
import { Observable } from 'rxjs';

@Component({
  selector: 'app-panel-layout',
  templateUrl: './panel-layout.component.html',
  styleUrls: ['./panel-layout.component.css']
})
export class PanelLayoutComponent implements OnInit {
    
  ordenes:Observable<Orden[]>; 
  public view_table:boolean;
  constructor(private ordenService:OrdenService) 
  {
    this.view_table=false;
   }

  ngOnInit() {  
  }


  verActividaes(){
    var result = document.getElementById("fecha");
    var fecha=<HTMLInputElement>result["value"];
    if(fecha){
      this.view_table=true;
      this.ordenes=this.ordenService.getActivitiesToDay(fecha);
    }else{
      this.view_table=false;
      alert("Seleccione una fecha");
    }
    
  }
}
