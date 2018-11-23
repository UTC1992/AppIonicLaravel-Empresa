import { Component, OnInit,Input } from '@angular/core';
import { OrdenService } from '../../services/orden.service';
import { Orden } from '../../models/orden';
import { Observable } from 'rxjs';
import {Router} from "@angular/router";

@Component({
  selector: 'app-table-client',
  templateUrl: './table-client.component.html',
  styleUrls: ['./table-client.component.css']
})
export class TableClientComponent implements OnInit {
 
  p:number=1;
  total:number=0;
  ordenes:Observable<Orden[]>; 

  constructor(private ordenService:OrdenService,private router:Router ) { }

  ngOnInit() {
    
    this.cargarDatos();
  }

  cargarDatos(){
    this.ordenes =this.ordenService.getOrdenes();
    this.ordenes.subscribe(
      result=>{
        this.total=result.length;
      });
  }
 

}
