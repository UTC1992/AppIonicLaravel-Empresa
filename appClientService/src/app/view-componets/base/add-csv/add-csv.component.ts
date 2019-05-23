import { Component, OnInit,ElementRef, ViewChild } from '@angular/core';
import { OrdenService } from '../../../services/orden.service';
import {FormBuilder, FormGroup, Validators, FormControl} from "@angular/forms";
import { Observable } from 'rxjs';

import { TableClientComponent } from '../table-client/table-client.component';

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
  selector: 'app-add-csv',
  templateUrl: './add-csv.component.html',
  styleUrls: ['./add-csv.component.css'],
  providers: [
    {provide: DateAdapter, useClass: MomentDateAdapter, deps: [MAT_DATE_LOCALE]},

    {provide: MAT_DATE_FORMATS, useValue: MY_FORMATS},
  ],
})
export class AddCsvComponent implements OnInit {
  //subida de excel
  error: string;
  uploadResponse = { status: '', message: '', filePath: '' };
  progresoMostrar: boolean = false;
  
  @ViewChild(TableClientComponent) tablaCliente: TableClientComponent;

  today:Date;
  form: FormGroup;
  loading2: boolean = false;
  public loading:boolean;
  loadingRecManual: boolean = false;
  resultValida:Observable<any>;
  public validaReconexiones:boolean;

  //fecha borrado
  fechaBorrado: string = null;
  dateBorrado = new FormControl(
    'date', [
      Validators.required
    ]
  );
  serializedDate = new FormControl((new Date()).toUTCString());

  @ViewChild('fileInput') fileInput: ElementRef;
  constructor(
      private ordenService:OrdenService,
      private fb: FormBuilder,
      private dateAdapter: DateAdapter<Date>
  ) {
    this.dateAdapter.setLocale('es'); 
    this.createForm();
    this.loading=false;
    this.validaReconexiones=false;
    this.today =new Date();
   }

  ngOnInit() {
    this.reloadTableClient();

    this.form = this.fb.group({
      archivo: ['']
    });
  }

  getFechaBorrar(pickerInput: string): void {
    this.fechaBorrado = pickerInput;
    //console.log(this.fechaBuscar);
  }

  //metodo envia file al servidor    
  onSubmit(){
    if(this.form.get('archivo').value==null || this.form.get('archivo').value==""){
      this.showAlert('Alerta!',"Seleccione un archivo",'warning');
      return;
    }

    let input = new FormData();
    input.append('archivo', this.form.get('archivo').value);

    this.progresoMostrar = false;

    this.ordenService.addCsvFiles(input)
    .subscribe(
      response=>{
        //console.log(response);
        this.uploadResponse = response;
        if(this.uploadResponse.message == '100'){
          //this.showAlert('Éxito!',"Archivo subido correctamente",'success');
          this.clearFile();
          this.reloadTableClient();
          this.progresoMostrar = true;
        }
        if(this.uploadResponse.message != '100'){
          this.progresoMostrar = false;
        }

      },
      error=>{
        console.log(<any>error);
        this.showAlert('Alerta!',"Error, No se pudo subir el archivo", 'warning');
      });
  }


  reloadTableClient(){
    this.tablaCliente.cargarDatos();
  }

  createForm() {
    this.form = this.fb.group({
      archivo: null
    });
  }

  onFileChange(event) {
    if(event.target.files.length > 0) {
      let file = event.target.files[0];
      this.form.get('archivo').setValue(file);
    }
  }

  prepareSave(): any {
    let input = new FormData();
    input.append('archivo', this.form.get('archivo').value);
    return input;
  }

  //subir archivo al servidor 
  /*onSubmit1() {
    if(this.form.get('archivo').value==null || this.form.get('archivo').value==""){
      alert("Seleccione un archivo");
      return;
    }
    //this.loading = true;
    
    const formModel = this.prepareSave();
    //this.loading2 = true;
    this.ordenService.addCsvFiles(formModel)
    .subscribe(
      msj=>{
        if(msj){
          //this.loading = false;
          
          alert("Archivo subido correctamente");
          this.clearFile();
          this.reloadTableClient();
        }else{
          
          alert("Ocurrio un error");
        }
      }
    );
  }
  */

  clearFile() {
    this.form.get('archivo').setValue(null);
    this.fileInput.nativeElement.value = '';
  }

  validarRecManuales(){
    //this.loadingRecManual = true;
    
    this.resultValida=this.ordenService.validarReconexionesManuales();
    this.resultValida.subscribe(
      msj=>{
        if(msj){
          //this.loadingRecManual = false;
          
          this.showAlert('Éxito!',"Proceso realizado correctamente", 'success');
          this.reloadTableClient();
        }else{
          
          alert(msj);
        }
      });
  }

  confirmarEliminar(){
    if(this.fechaBorrado != null){
      Swal.fire({
        title: 'Alerta de ¡Borrado!',
        text: 'Deseas borrar las actividades subidas en la fecha seleccionada,'+
              ' también se eliminarán las asignaciones realizadas a los tecnicos,'+ 
              ' recuerda que el borrado es permanente.',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '¿Deseas borrar de todas formas?',
        allowOutsideClick: false
      }).then((result) => {
        if (result.value) {
          this.eliminarActividades();
        }
      });
    } else {
      this.showAlert("","Debe elegir la fecha en que subio los datos.","warning");
    }
    
  }

  // eliminar actividades
  eliminarActividades(){
   
    if(this.fechaBorrado != null){
      var date = this.fechaBorrado;
      var vector = date.split("-");
      var fecha=vector[2]+"-"+vector[1]+"-"+vector[0];
      let data={
        'fecha':fecha
      }
      this.ordenService.deleteActivities(data).subscribe(
        result=>{
          if(result){
            console.log(result);
            this.showAlert("Éxito!",'Actividades borradas correctamente!',"success");
            this.reloadTableClient();
          }else{
            this.showAlert("Alerta!",'No existen resgistros en esa fecha para borrarlos.',"warning");
            this.reloadTableClient();
          }
        }
      );
    }
    
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
