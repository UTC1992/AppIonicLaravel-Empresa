import { Component, OnInit } from '@angular/core';
import { OrdenService } from '../../services/orden.service';
import { Recmanual } from '../../models/recmanual';
import { Observable } from 'rxjs';
import {Router} from "@angular/router";

@Component({
  selector: 'app-table-recmanual',
  templateUrl: './table-recmanual.component.html',
  styleUrls: ['./table-recmanual.component.css']
})
export class TableRecmanualComponent implements OnInit {

	recmanuales:Observable<Recmanual[]>; 
	view_tableRecmanual: boolean;
	constructor(
		private ordenService:OrdenService,
		private router:Router
		) { }

	ngOnInit() {
	}

	cargarDatos(data){
	    this.recmanuales =this.ordenService.getRecManual(data);
	    this.recmanuales.subscribe(
	    	data => {
	    		console.log(data);
	    	});
	    this.mostrarRecManuales();
	}

	mostrarRecManuales(){
		this.view_tableRecmanual = true;
	}

	ocultarRecManuales(){
		this.view_tableRecmanual = false;
	}

}
