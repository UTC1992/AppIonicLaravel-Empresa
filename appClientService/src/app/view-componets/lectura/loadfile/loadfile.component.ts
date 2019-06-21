import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import {FormBuilder, FormGroup, Validators, FormControl} from "@angular/forms";
import { LecturasService } from '../../../services-lecturas/lecturas.service';

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

  ) {
    this.dateAdapter.setLocale('es'); 
    this.createForm();
  }

  ngOnInit() {
  }

  getFechaBorrar(pickerInput: string): void {
    this.fechaBorrado = pickerInput;
    //console.log(this.fechaBuscar);
  }

  createForm() {
    this.formload = this.formbuilder.group({
      archivo: null
    });
  }

  /**
   * 
   */
  onSubmit(){
    if(this.formload.get('archivo').value==null || this.formload.get('archivo').value==""){
      this.showAlert('Alerta!',"Seleccione un archivo",'warning');
      return;
    }
    const formModel = this.prepareSave();
    this.lecturaService.uploadFile(formModel).subscribe(
      result=>{
        console.log(result);
        this.showAlert('Éxito','Datos subidos exitosamente','success');
        this.clearFile();
      }
    );
    
  }

  /**
   * 
   */
  prepareSave(): any {
    let input = new FormData();
    input.append('file', this.formload.get('archivo').value);
    return input;
  }

  /**
   * 
   */
  clearFile() {
    this.formload.get('archivo').setValue(null);
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
    this.lecturaService.procesarDatosSubidos().subscribe(response => {
      console.log(response);
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
  

}
