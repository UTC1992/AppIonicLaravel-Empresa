import { Component, OnInit } from '@angular/core';
import { TecnicoService } from '../../services/tecnico.service';
import { Tecnico } from '../../models/tecnico';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import { Observable } from 'rxjs';
import { NgxSpinnerService } from 'ngx-spinner';

@Component({
  selector: 'app-tecnicos',
  templateUrl: './tecnicos.component.html',
  styleUrls: ['./tecnicos.component.css']
})
export class TecnicosComponent implements OnInit {
  form_tecnico_edicion: FormGroup;
  loading: boolean = false;
  tecn:any;
  tecnicos:Observable<Tecnico[]>; 
  constructor(
              private tecnicoService:TecnicoService,
              private formBuilder: FormBuilder,
              private spinner: NgxSpinnerService
  ) { 
    this.createForm();
  }

  ngOnInit() {
    this.tecnicos =this.tecnicoService.getAllTecnicos();
  }

  deleteTecnico(id){
    let res=this.tecnicoService.deleteTecnico(id)
    .subscribe(
      resp=>{
        if(resp){
          console.log(resp);
          alert('Borrado Correctamente');
          location.reload();   
        } else {
          console.log(resp);
          alert('No se borro el técnico');
        }
      });
    
     
  }
  updateTecnico(id){
    alert(id);
  }
  open(id) {
    this.tecn=this.tecnicoService.getTecnicoById(id).subscribe(
      res=>{this.tecn= this.createformEdit(res)}
    );
  }

  createForm() {
    this.form_tecnico_edicion = this.formBuilder.group({
      id: ['', Validators.required],
      nombres: ['', Validators.required],
      apellidos: ['', Validators.required],
      cedula: ['', Validators.required],
      telefono: ['', Validators.required],
      email: ['', Validators.required],
      estado:['', Validators.required]
    });
  }

  createformEdit(object){
    this.form_tecnico_edicion = this.formBuilder.group({
      id:[object.id_tecn, Validators.required],
      nombres: [object.nombres, Validators.required],
      apellidos: [object.apellidos, Validators.required],
      cedula: [object.cedula, Validators.required],
      telefono: [object.telefono, Validators.required],
      email: [object.email, Validators.required],
      estado:['1', Validators.required]
    });
  }

  private prepareSave(): any {
    console.log("APELLIDOS"+this.form_tecnico_edicion.get('apellidos').value);
    let input = new FormData();
    input.append('id_tecn', this.form_tecnico_edicion.get('id').value);
    input.append('nombres', this.form_tecnico_edicion.get('nombres').value);
    input.append('apellidos', this.form_tecnico_edicion.get('apellidos').value);
    input.append('cedula', this.form_tecnico_edicion.get('cedula').value);
    input.append('telefono', this.form_tecnico_edicion.get('telefono').value);
    input.append('email', this.form_tecnico_edicion.get('email').value);
    input.append('estado', this.form_tecnico_edicion.get('estado').value);
    return input;
  }

  onSubmitEdit(){
    this.spinner.show();
    const formModel = this.prepareSave();
    //this.loading = true;
    this.tecnicoService.updateTecnico(formModel)
    .subscribe(
      file=>console.log(file),
      error=>console.log(<any>error)
    );
    
    setTimeout(() => {
      alert('Técnico actualizado correctamente!');
      //this.loading = false;
      this.spinner.hide();
      location.reload();
    }, 1000);
  }
}
