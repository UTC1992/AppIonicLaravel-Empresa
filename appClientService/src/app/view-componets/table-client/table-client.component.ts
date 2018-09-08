import { Component, OnInit,Input } from '@angular/core';
import { OrdenService } from '../../services/orden.service';
import { Orden } from '../../models/orden';
import { Observable } from 'rxjs';

@Component({
  selector: 'app-table-client',
  templateUrl: './table-client.component.html',
  styleUrls: ['./table-client.component.css']
})
export class TableClientComponent implements OnInit {
 

  ordenes:Observable<Orden[]>; 

  constructor(private ordenService:OrdenService ) { }

  ngOnInit() {
    this.ordenes =this.ordenService.getOrdenes();
  }

}
