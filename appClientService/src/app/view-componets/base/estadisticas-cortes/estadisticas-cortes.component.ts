import { Component, OnInit } from '@angular/core';
import { ChartOptions, ChartType, ChartDataSets } from 'chart.js';
import * as pluginDataLabels from 'chartjs-plugin-datalabels';
import { Label } from 'ng2-charts';
import {FormBuilder, FormGroup, Validators, FormControl} from '@angular/forms';
import {MomentDateAdapter} from '@angular/material-moment-adapter';
import {DateAdapter, MAT_DATE_FORMATS, MAT_DATE_LOCALE} from '@angular/material/core';
import { ReportesCortes } from '../../../services/reportes-cortes.service';
import { LoginService } from '../../../services/login.service';

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
  selector: 'app-estadisticas-cortes',
  templateUrl: './estadisticas-cortes.component.html',
  styleUrls: ['./estadisticas-cortes.component.css'],
  providers: [
    {provide: DateAdapter, useClass: MomentDateAdapter, deps: [MAT_DATE_LOCALE]},

    {provide: MAT_DATE_FORMATS, useValue: MY_FORMATS},
  ],
})
export class EstadisticasCortesComponent implements OnInit {

  //consolidado
  fechaDiarioInicio: string = null;
  dateDiarioInicio = new FormControl(
    'date', [
      Validators.required
    ]
  );

  fechaDiarioFin: string = null;
  dateDiarioFin = new FormControl(
    'date', [
      Validators.required
    ]
  );

  public barChartOptions: ChartOptions = {
    responsive: true,
    // We use these empty structures as placeholders for dynamic theming.
    scales: { xAxes: [{}], yAxes: [{}] },
    plugins: {
      datalabels: {
        anchor: 'end',
        align: 'end',
      }
    }
  };

  public barChartLabels: Label[] = ['00-00-0000'];
  public barChartType: ChartType = 'bar';
  public barChartLegend = true;
  public barChartPlugins = [pluginDataLabels];

  public barChartData: ChartDataSets[] = [
    { data: [], label: 'COR' },
    { data: [], label: 'NOT' },
    { data: [], label: 'REC' }
  ];



  constructor(
    private reporteService: ReportesCortes,
    private loginService: LoginService
  ) { }

  ngOnInit() {
  }

  mostrarReporteDiario(){
    let empresa = this.loginService.usuario.id_emp;
    var dateInicio = this.fechaDiarioInicio;
    var vectorInicio = dateInicio.split("-");
    var fechaInicio=vectorInicio[2]+"-"+vectorInicio[1]+"-"+vectorInicio[0];

    var dateFin = this.fechaDiarioFin;
    var vectorFin = dateFin.split("-");
    var fechaFin=vectorFin[2]+"-"+vectorFin[1]+"-"+vectorFin[0];
    let data: any[] = [];
    data.push({ 
      'empresa': empresa,
      'inicio': fechaInicio,
      'fin': fechaFin
    });
    //console.log(data);
    this.reporteService.getCortesDiarios(data).subscribe(response =>{
      //console.log(response);
      for(let i = 0; i < response.length; i++){
        this.barChartLabels[i] = response[i].fecha;
        this.barChartData[i].data[0] = response[i].cantidad;
      }
       
    });
  }

  getFechaInicio(pickerInput: string): void {
    this.fechaDiarioInicio = pickerInput;
    //console.log(this.fechaBuscar);
  }
  getFechaFin(pickerInput: string): void {
    this.fechaDiarioFin = pickerInput;
    //console.log(this.fechaBuscar);
  }

  // events
  public chartClicked({ event, active }: { event: MouseEvent, active: {}[] }): void {
    //console.log(event, active);
  }

  public chartHovered({ event, active }: { event: MouseEvent, active: {}[] }): void {
    //console.log(event, active);
  }

}
