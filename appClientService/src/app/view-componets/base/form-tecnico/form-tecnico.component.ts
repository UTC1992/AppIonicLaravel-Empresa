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
    if(!this.validarCedula(this.form_tecnico.get('cedula').value)){
      alert('cedula incorrecta');
      this.spinner.hide();
      return;
    }

    
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


  validarCedula(cedula_tecnico){
    let cedula = cedula_tecnico;
      
     if(cedula.length == 10){
        var digito_region = cedula.substring(0,2);
        if(parseInt(digito_region) >= 1 && parseInt(digito_region) <=24 ){
          var ultimo_digito   = cedula.substring(9,10);
          var pares = parseInt(cedula.substring(1,2)) + parseInt(cedula.substring(3,4)) + parseInt(cedula.substring(5,6)) + parseInt(cedula.substring(7,8));
           numero1 = cedula.substring(0,1);
            numero1 = (numero1 * 2);
          if( numero1 > 9 ){ var numero1 = (numero1 - 9); }
           numero3 = cedula.substring(2,3);
          numero3 = (numero3 * 2);
          if( numero3 > 9 ){ var numero3 = (numero3 - 9); }

          numero5 = cedula.substring(4,5);
          numero5 = (numero5 * 2);
          if( numero5 > 9 ){ var numero5 = (numero5 - 9); }

          numero7 = cedula.substring(6,7);
          numero7 = (numero7 * 2);
          if( numero7 > 9 ){ var numero7 = (numero7 - 9); }

         numero9 = cedula.substring(8,9);
           numero9 = (numero9 * 2);
          if( numero9 > 9 ){ var numero9 = (numero9 - 9); }

          var impares = numero1 + numero3 + numero5 + numero7 + numero9;

          var suma_total = (pares + impares);

          var primer_digito_suma = String(suma_total).substring(0,1);

          var decena = (parseInt(primer_digito_suma) + 1)  * 10;

          var digito_validador = decena - suma_total;

          if(digito_validador == 10)
            var digito_validador = 0;

          if(digito_validador == parseInt(ultimo_digito)){
            return true;
          }else{
            return false;
          }
          
        }else{
          return false;
        }
     }else{
      return false;
     }    
  }
  
}
