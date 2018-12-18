import { Component, OnInit } from '@angular/core';
import { OrdenService } from '../../services/orden.service';
import { Observable } from 'rxjs';
import {Router} from "@angular/router";
import { ExcelServiceService } from '../../services/excel-service.service';

@Component({
  selector: 'app-table-recmanual',
  templateUrl: './table-recmanual.component.html',
  styleUrls: ['./table-recmanual.component.css']
})
export class TableRecmanualComponent implements OnInit {

	recmanuales:Observable<any[]>; 
	view_tableRecmanual: boolean;
	view_data_empty_recmanual: boolean;
	constructor(
		private ordenService:OrdenService,
		private router:Router,
		private excelService:ExcelServiceService,
		) { }

	ngOnInit() {
	}

	cargarDatos(data){
	    this.recmanuales =this.ordenService.getRecManual(data);
	    this.recmanuales.subscribe(
	    	data => {
	    		//console.log(data);
	    		if(data.length>0){
		            this.mostrarRecManuales();
		            this.view_data_empty_recmanual=false;
		          }else{
		            this.ocultarRecManuales();
		            this.view_data_empty_recmanual=true;
		          }
	    	});
	    
	}

	mostrarRecManuales(){
		this.view_tableRecmanual = true;
	}

	ocultarRecManuales(){
		this.view_tableRecmanual = false;
	}

	ocultarEmptyRecManuales(){
		this.view_data_empty_recmanual=false;
	}

	exportarExcelRecManual(fecha:any){
	    let datos = Array();
	    if(this.recmanuales != null && this.view_tableRecmanual==true){
	      this.recmanuales.subscribe(
	        data=>{
	          for (var i = 0; i < data.length; ++i) {
	            datos.push({
	                      MEDIDOR:      data[i]['medidor'],
	                      LECTURA:      data[i]['lectura'],
	                      NOVEDADES:    data[i]['observacion'],
	                      FOTO:       data[i]['foto']
	                    });
	          }

	          this.excelService.exportAsExcelFile(datos,fecha+'_RecManuales');
	        });
	    }
	}

	

}
