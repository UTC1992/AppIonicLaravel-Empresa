import { Component, OnInit,ElementRef, ViewChild } from '@angular/core';
import {MomentDateAdapter} from '@angular/material-moment-adapter';
import {DateAdapter, MAT_DATE_FORMATS, MAT_DATE_LOCALE} from '@angular/material/core';
import Swal from 'sweetalert2';
import { Url } from '../../../models/Url';
import {FormBuilder, FormGroup, Validators, FormControl} from '@angular/forms';
//services
import { TecnicolecService } from "../../../services-lecturas/tecnicolec.service";
import { ConsultaService } from "../../../services-lecturas/consulta.service";
//models
import { Tecnico } from "../../../models/tecnico";
import {formatDate } from '@angular/common';
//tablas
import {MatTableDataSource, MatPaginator} from '@angular/material';


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
  selector: 'app-consultas',
  templateUrl: './consultas.component.html',
  styleUrls: ['./consultas.component.css'],
  providers: [
    {provide: DateAdapter, useClass: MomentDateAdapter, deps: [MAT_DATE_LOCALE]},

    {provide: MAT_DATE_FORMATS, useValue: MY_FORMATS},
  ],
})
export class ConsultasComponent implements OnInit {

  //fecha de filtro
  mesBuscar: number = 0;
  lectorBuscar: string = 'empty';
  agenciaBuscar: string = 'empty';
  estadoBuscar: string = 'empty';

  date = new FormControl(
    'date', [
      Validators.required
    ]
  );

  //tecnicos select
  tecnicosLecturas:Tecnico[]=[];

  //meses
  mesesList: any[] = [];

  //tabla
  displayedColumns: string[] = ['index', 'tecnico', 'agencia', 'sector', 'ruta', 'cuenta',
                                'medidor', 'lec_anterior', 'lec_actual', 'usuario', 'latitud',
                                'longitud', 'hora', 'observacion', 'fechalec'];
  dataSource = new MatTableDataSource();
  @ViewChild(MatPaginator) paginator: MatPaginator;

  constructor(
    private tecnicoService:TecnicolecService,
    private consultaService:ConsultaService,
    private adapter: DateAdapter<any>,
    private fb: FormBuilder,
  ) { }

  ngOnInit() {
    this.llenarTecnicosSelect();
    this.llenarMeses();
  }

  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }

  llenarTecnicosSelect(){
    this.tecnicoService.getTecnicosLecturas().subscribe(response => {
      console.log(response);
      this.tecnicosLecturas = response;
    });
  }

  getFecha(pickerInput: string): void {
    //this.fechaBuscar = pickerInput;
    //console.log(this.fechaBuscar);
  }

  llenarMeses(){
    this.mesesList = [];
    let meses = ['Enero', 'Febrero','Marzo','Abril','Mayo','Junio',
                'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre',]
    for (let i = 0; i < 12; i++) {
      this.mesesList.push({
        numero: (i+1),
        mes: meses[i]
      });
    }
  }

  verProgresoLecturas(){
    //obtener mes actual
    let fecha = new Date();
    console.log(formatDate(fecha, "yyyy-MM-dd",'en'));
    let fechaActual = formatDate(fecha, "yyyy-MM-dd",'en');
    //obtener datos anio mes dia separados 
    let vectorActual = fechaActual.split('-');
    let mesActual = parseInt(vectorActual[1]);

    //crear objeto para enviar filtros
    let data: any[] = [];
      data.push({
        fecha : this.mesBuscar,
        lector : this.lectorBuscar,
        agencia : this.agenciaBuscar,
        estado : this.estadoBuscar,
      });
      console.log(data);

    if(mesActual == this.mesBuscar){
      console.log("Meses iguales");
      
      this.consultaService.getDataProgreso(data).subscribe(response => {
        console.log(response);
        this.dataSource = new MatTableDataSource(response);
        this.dataSource.paginator = this.paginator;
      });
      
    } else if (mesActual > this.mesBuscar) {
      console.log("Meses pasados");
      this.consultaService.getDataProgreso(data).subscribe(response => {
        console.log(response);
        this.dataSource = new MatTableDataSource(response);
        this.dataSource.paginator = this.paginator;
      });  
    }
    
  }

}
