import { Component, OnInit, TemplateRef } from '@angular/core';
import { TecnicoService } from '../../../services/tecnico.service';
import { Tecnico } from '../../../models/tecnico';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import { Observable } from 'rxjs';
import { NgxSpinnerService } from 'ngx-spinner';
import {BsModalRef, BsModalService,  } from 'ngx-bootstrap/modal';
import {MatTableDataSource} from '@angular/material';

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

  displayedColumns: string[] = ['id_tecn', 'nombres', 'cedula', 'telefono', 'email', 'estado','actividad', 'acciones'];
  dataSource = new MatTableDataSource();

  constructor(
    private tecnicoService:TecnicoService,
    private formBuilder: FormBuilder,
    private spinner: NgxSpinnerService,
    private modalService: BsModalService,
    public modalRef: BsModalRef,
  ) { 
    this.createForm();
  }

  ngOnInit() {
    this.mostrarTecnicos();
  }

  mostrarTecnicos(){
    this.tecnicoService.getAllTecnicos().subscribe(res =>{
      this.dataSource = new MatTableDataSource(res);
    });
    
  }

  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
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
  open(id, template: TemplateRef<any>) {
    this.modalRef = this.modalService.show(template);
    this.tecn=this.tecnicoService.getTecnicoById(id).subscribe(
      res=>{
        this.tecn= this.createformEdit(res);
      }
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
      actividad:['',Validators.required],
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
      actividad:[object.actividad,Validators.required],
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
    input.append('actividad', this.form_tecnico_edicion.get('actividad').value);
    input.append('estado', this.form_tecnico_edicion.get('estado').value);
    return input;
  }

  onSubmitEdit(){
    this.spinner.show();
    const formModel = this.prepareSave();
    //this.loading = true;
    if(!this.validarCedula(this.form_tecnico_edicion.get('cedula').value)){
      alert('cedula incorrecta');
      this.spinner.hide();
      return;
    }
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
