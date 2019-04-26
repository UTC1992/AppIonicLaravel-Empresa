import { Component, OnInit,ElementRef, ViewChild } from '@angular/core';
import { OrdenService } from '../../../services/orden.service';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import { Observable } from 'rxjs';

import { TableClientComponent } from '../table-client/table-client.component';

import {MatDialog, MatDialogConfig} from "@angular/material";
import { AlertaDeleteComponent } from '../alerta-delete/alerta-delete.component';

import { NativeDateAdapter, DateAdapter } from "@angular/material";

@Component({
  selector: 'app-add-csv',
  templateUrl: './add-csv.component.html',
  styleUrls: ['./add-csv.component.css']
})
export class AddCsvComponent implements OnInit {

  @ViewChild(TableClientComponent) tablaCliente: TableClientComponent;

  today:Date;
  form: FormGroup;
  loading2: boolean = false;
  public loading:boolean;
  loadingRecManual: boolean = false;
  resultValida:Observable<any>;
  public validaReconexiones:boolean;

  @ViewChild('fileInput') fileInput: ElementRef;
  constructor(
      private ordenService:OrdenService,
      private fb: FormBuilder,
      private dialog: MatDialog,
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
   
   
  }
  //metodo envia file al servidor    
  uploadCsvFile(file){
    this.ordenService.addCsvFiles(file.archivo)
    .subscribe(
      file=>console.log(file),
      error=>console.log(<any>error)
    );
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
  onSubmit() {
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
          
          alert("Proceso Realizado Correctamente");
          this.reloadTableClient();
        }else{
          
          alert(msj);
        }
      });
  }

  validarBorradoDeActividades(){
      const dialogConfig = new MatDialogConfig();

    dialogConfig.disableClose = true;
    dialogConfig.autoFocus = true;

    dialogConfig.data = {
      title: 'Alerta de ¡Borrado!',
      description:  'Deseas borrar las actividades subidas la fecha seleccionada,'+
      ' también se eliminarán las asignaciones realizadas a los tecnicos,'+ 
                    ' recuerda que el borrado es permanente.'+
                    ' ¿Deseas realizar está acción?',
      height: '400px',
      width: '100px',
    };

    
    const dialogRef = this.dialog.open(AlertaDeleteComponent, dialogConfig);

    dialogRef.afterClosed().subscribe(
        data => {
          if(data){
            //console.log("Datos borrados");
            this.eliminarActividades();
          } else {
            console.log("Cancelar operación de borrado");
          }
        });
  }

  // eliminar actividades
  eliminarActividades(){
    var fecha = <HTMLInputElement>document.getElementsByName("fecha_borrado")[0]["value"];
   
    if(fecha){
      console.log('ingresando a borrar');
    if(localStorage.getItem("token")!=null){
      let data={
        'fecha':fecha
      }
      this.ordenService.deleteActivities(data).subscribe(
        result=>{
          if(result){
            console.log(result);
              alert('Actividadas borradas correctamente!');
              location.reload();
          }else{
            alert('No existen resgistros para borrar');
          }
        }
      );
    }
    }
    
  }

}
