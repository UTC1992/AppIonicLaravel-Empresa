import { Component, OnInit,ElementRef, ViewChild } from '@angular/core';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import { TecnicoService } from '../../../services/tecnico.service';

import { NgxSpinnerService } from 'ngx-spinner';

@Component({
  selector: 'app-form-tecnico',
  templateUrl: './form-tecnico.component.html',
  styleUrls: ['./form-tecnico.component.css']
})
export class FormTecnicoComponent implements OnInit {

  form_tecnico: FormGroup;
  loading: boolean = false;
 
  constructor(private formBuilder: FormBuilder, 
              private tecnicoService:TecnicoService,
              private spinner: NgxSpinnerService
  ) {
    this.createForm();
   }
  
  ngOnInit() {

  }
   //metodo envia file al servidor    
   uploadCsvFile(file){
    
  }

  createForm() {
    this.form_tecnico = this.formBuilder.group({
      nombres: ['', Validators.required],
      apellidos: ['', Validators.required],
      cedula: ['', Validators.required],
      telefono: ['', Validators.required],
      email: ['', Validators.required],
      estado:['1', Validators.required],
      actividad:['',Validators.required]
    });
  }
  /*
  onFileChange(event) {
    if(event.target.files.length > 0) {
      let file = event.target.files[0];
      this.form_tecnico.get('archivo').setValue(file);
    }
  }
  */
  private prepareSave(): any {
    console.log("APELLIDOS"+this.form_tecnico.get('apellidos').value);
    let input = new FormData();
    input.append('nombres', this.form_tecnico.get('nombres').value);
    input.append('apellidos', this.form_tecnico.get('apellidos').value);
    input.append('cedula', this.form_tecnico.get('cedula').value);
    input.append('telefono', this.form_tecnico.get('telefono').value);
    input.append('email', this.form_tecnico.get('email').value);
    input.append('actividad', this.form_tecnico.get('actividad').value);
    input.append('estado', this.form_tecnico.get('estado').value);
    return input;
  }
  onSubmit() {
    this.spinner.show();
    const formModel = this.prepareSave();
    //this.loading = true;
    
    this.tecnicoService.insertTecnico(formModel)
    .subscribe(
      file=>{
        if(file){
          alert('Técnico creado correctamente!');
          this.spinner.hide();
          location.reload();
        }else{
          alert('Cédula repetida !');
          this.spinner.hide();
        }
      }

    );

   
  }

  clearFile() {
    this.form_tecnico.get('nombres').setValue(null);
    this.form_tecnico.get('apellidos').setValue(null);
    this.form_tecnico.get('cedula').setValue(null);
    this.form_tecnico.get('telefono').setValue(null);
    this.form_tecnico.get('email').setValue(null);
    this.form_tecnico.get('actividad').setValue(null);
  }
  
}
