import { Component, OnInit,ElementRef, ViewChild } from '@angular/core';
import { OrdenService } from '../../services/orden.service';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";

@Component({
  selector: 'app-add-csv',
  templateUrl: './add-csv.component.html',
  styleUrls: ['./add-csv.component.css']
})
export class AddCsvComponent implements OnInit {
  form: FormGroup;
  loading2: boolean = false;
  @ViewChild('fileInput') fileInput: ElementRef;
  constructor(private ordenService:OrdenService,private fb: FormBuilder) {
    this.createForm();
   }

  ngOnInit() {
  }
  //metodo envia file al servidor    
  uploadCsvFile(file){
    this.ordenService.addCsvFiles(file.archivo)
    .subscribe(
      file=>console.log(file),
      error=>console.log(<any>error)
    );
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
  private prepareSave(): any {
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
    const formModel = this.prepareSave();
    this.loading2 = true;
    this.ordenService.addCsvFiles(formModel)
    .subscribe(
      file=>console.log(file),
      error=>console.log(<any>error)
    );
    this.clearFile();
    setTimeout(() => {
      alert('Archivo subido correctamente!');
      this.loading2 = false;
      location.reload();
    }, 1000);
   
  }

  clearFile() {
    this.form.get('archivo').setValue(null);
    this.fileInput.nativeElement.value = '';
  }


  


}
