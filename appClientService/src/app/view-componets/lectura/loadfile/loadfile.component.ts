import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import {FormBuilder, FormGroup, Validators, FormControl} from "@angular/forms";
import { LecturasService } from '../../../services-lecturas/lecturas.service';
import { ValidacionService } from '../../../services-lecturas/validacion.service';

import {MomentDateAdapter} from '@angular/material-moment-adapter';
import {DateAdapter, MAT_DATE_FORMATS, MAT_DATE_LOCALE} from '@angular/material/core';

import Swal from 'sweetalert2';

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
  selector: 'app-loadfile',
  templateUrl: './loadfile.component.html',
  styleUrls: ['./loadfile.component.css'],
  providers: [
    {provide: DateAdapter, useClass: MomentDateAdapter, deps: [MAT_DATE_LOCALE]},

    {provide: MAT_DATE_FORMATS, useValue: MY_FORMATS},
  ],
})
export class LoadfileComponent implements OnInit {

  @ViewChild('fileInput') fileInput: ElementRef;

  formload: FormGroup;

  //meses
  mesElegido: any = null;
  meses: any[] = [];

  //fecha borrado
  fechaBorrado: string = null;
  dateBorrado = new FormControl(
    'date', [
      Validators.required
    ]
  );

  constructor(
    private formbuilder: FormBuilder,
    private lecturaService: LecturasService,
    private dateAdapter: DateAdapter<Date>,
    private validacionService: ValidacionService

  ) {
    this.dateAdapter.setLocale('es'); 
    this.createForm();
  }

  ngOnInit() {
    this.llenarMeses();
  }

  llenarMeses(){
    let mesesVector = ["Enero","Febrero","Marzo","Abril","Mayo","Junio",
                      "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
    for (let i = 0; i < 12; i++) {
      this.meses.push({
        numero: (i+1),
        nombre:mesesVector[i]
      });
    }
    console.log(this.meses);
  }

  getFechaBorrar(pickerInput: string): void {
    this.fechaBorrado = pickerInput;
    //console.log(this.fechaBuscar);
  }

  createForm() {
    this.formload = this.formbuilder.group({
      archivo: [null],
      mes:[null]
    });
  }

  /**
   * 
   */
  onSubmit(){
    console.log(this.formload.value);
    this.showCargando();
    if(this.formload.get('archivo').value==null || this.formload.get('archivo').value==""){
      this.showAlert('Alerta!',"Seleccione un archivo",'warning');
      return;
    }
    if(this.formload.get('mes').value==null || this.formload.get('mes').value==""){
      this.showAlert('Alerta!',"Seleccione un mes",'warning');
      return;
    }
    const formModel = this.prepareSave();
    this.lecturaService.uploadFile(formModel).subscribe(result=>{
        console.log(result);
        this.showAlert('Éxito','Datos subidos exitosamente','success');
        this.clearFile();
      }, error => {
        console.log(error);
    });
    
  }

  /**
   * 
   */
  prepareSave(): any {
    let input = new FormData();
    input.append('file', this.formload.get('archivo').value);
    input.append('mes', this.formload.get('mes').value);
    return input;
  }

  /**
   * 
   */
  clearFile() {
    this.formload.get('archivo').setValue(null);
    this.formload.get('mes').setValue(null);
    this.fileInput.nativeElement.value = '';
  }

  /**
   * 
   * @param event 
   */
  onFileChange(event) {
    if(event.target.files.length > 0) {
      let file = event.target.files[0];
      this.formload.get('archivo').setValue(file);
    }
  }

  enviarDatosATemporal(){
    this.showCargando();
    this.lecturaService.procesarDatosSubidos().subscribe(response => {
      console.log(response);
      this.showAlert("Éxito !","Los datos subidos han sido procesados exitosamente","success");
    }, error => {
      this.showAlert("Error !","Error al procesar los datos","error");
      console.log(error);
    });
  }

  calcularConsumos(){
    this.showCargando();
    this.validacionService.calcularConsumos().subscribe(response => {
      console.log(response);
      this.showAlert("Éxito !","Los consumos se han calculado exitosamente","success");
    }, error => {
      this.showAlert("Error !","Error al calcular consumos","error");
      console.log(error);
    });
  }

  validarConsumos(){
    this.showCargando();
    this.validacionService.validarConsumos().subscribe(response => {
      console.log(response);
      this.showAlert("Éxito !","Los consumos se han validado exitosamente","success");
    }, error => {
      this.showAlert("Error !","Error al validar los consumos","error");
      console.log(error);
    });
  }

  validarLecturasCero(){
    this.showCargando();
    this.validacionService.validarLecturasCero().subscribe(response => {
      console.log(response);
      this.showAlert("Éxito !","Las lecturas se han validado exitosamente","success");
    }, error => {
      this.showAlert("Error !","Error al validar las lecturas","error");
      console.log(error);
    });
  }



  showAlert(title, text, type){
    Swal.fire({
      title: title,
      text: text,
      type: type,
      allowOutsideClick: false
    });
  }
  
  showCargando(){
    let swal = Swal;
    swal.fire({
      title: 'Espere por favor...',
      showCloseButton: false,
      showCancelButton: false,
      showConfirmButton: false,
      allowOutsideClick: false,
      allowEscapeKey:false,
      onOpen: () => {
        Swal.showLoading();
      }
    });
  }

}
