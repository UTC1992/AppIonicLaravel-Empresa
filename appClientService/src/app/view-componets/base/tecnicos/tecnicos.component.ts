import { Component, OnInit, TemplateRef, ViewChild } from '@angular/core';
import { TecnicoService } from '../../../services/tecnico.service';
import { Tecnico } from '../../../models/tecnico';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import { Observable } from 'rxjs';
import {BsModalRef, BsModalService,  } from 'ngx-bootstrap/modal';
import {MatTableDataSource, MatPaginator} from '@angular/material';
import Swal from 'sweetalert2';

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
  @ViewChild(MatPaginator) paginator: MatPaginator;

  tituloModal: String;
  tipoAccion: String;

  formData: FormGroup;

  tecnicoEdit:any;

  constructor(
    private tecnicoService:TecnicoService,
    private formBuilder: FormBuilder,
    private modalService: BsModalService,
    public modalRef: BsModalRef,
  ) { 
    this.iniciarFormulario();
  }

  ngOnInit() {
    this.mostrarTecnicos();
  }

  mostrarTecnicos(){
    this.tecnicoService.getAllTecnicos().subscribe(res =>{
      this.dataSource = new MatTableDataSource(res);
      this.dataSource.paginator = this.paginator;
    });
    
  }

  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }

  deleteTecnico(id){
    this.tecnicoService.deleteTecnico(id)
    .subscribe(
      resp=>{
        if(resp){
          console.log(resp);
          Swal.fire(
            'Eliminado!',
            'El técnico a sido eliminado.',
            'success'
          )
          this.mostrarTecnicos();   
        } else {
          console.log(resp);
          Swal.fire(
            'Alerta!',
            'El técnico No a sido eliminado.',
            'warning'
          )
          this.mostrarTecnicos(); 
        }
      });
  }
  
  updateTecnico(id){
    alert(id);
  }

  openModalEdit(id, template: TemplateRef<any>) {
    this.tituloModal = "Editar datos del técnico";
    this.tipoAccion = "edit";
    this.tecn=this.tecnicoService.getTecnicoById(id).subscribe(
      response=>{
        //this.tecn= this.createformEdit(res);
        if(response != null){
          this.tecnicoEdit = this.iniciarFormularioEdit(response);
          //this.iniciarFormularioEdit();
          this.modalRef = this.modalService.show(template);
        }
      }
    );
  }

  openModalCreate(template: TemplateRef<any>) {
    this.iniciarFormulario();
    this.tipoAccion = "create";
    this.tituloModal = "Crear un nuevo técnico";
    this.modalRef = this.modalService.show(template);
  }

  iniciarFormularioEdit(object){
    this.formData = this.formBuilder.group({
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

  iniciarFormulario(){
    this.formData = this.formBuilder.group({
      nombres: ["", Validators.required],
      apellidos: ["", Validators.required],
      cedula: ["", Validators.required],
      telefono: ["", Validators.required],
      email: ["", Validators.required],
      actividad:["",Validators.required],
      estado:['1', Validators.required]
    });
  }

  private prepareSave(): any {
    console.log("APELLIDOS"+this.formData.get('apellidos').value);
    let input = new FormData();
    input.append('id_tecn', this.formData.get('id').value);
    input.append('nombres', this.formData.get('nombres').value);
    input.append('apellidos', this.formData.get('apellidos').value);
    input.append('cedula', this.formData.get('cedula').value);
    input.append('telefono', this.formData.get('telefono').value);
    input.append('email', this.formData.get('email').value);
    input.append('actividad', this.formData.get('actividad').value);
    input.append('estado', this.formData.get('estado').value);
    return input;
  }


  cerrarModal(){
    this.modalRef.hide();
  }

  enviarDatos(){
    console.log(this.formData.value);
    console.log("Tipo de accion ==> "+this.tipoAccion);
    if(this.tipoAccion == "create"){
      this.tecnicoService.insertTecnico(this.formData.value).subscribe(response => {
        console.log(response);
        if(response){
          console.log('Tecnico guardado');
          this.mostrarTecnicos();
          this.cerrarModal();
          Swal.fire('Éxito', 'Técnico creado con exito!', 'success');
        } else {
          console.log('Tecnico no guardado');
          Swal.fire('Alerta!', 'La cédula ya existe!', 'warning');
        }
      });
    }
    if(this.tipoAccion == "edit"){
      const formModel = this.prepareSave();
      //this.loading = true;
      this.tecnicoService.updateTecnico(formModel).subscribe( response =>{
        console.log(response);
        if(response){
          console.log('Tecnico editado');
          this.mostrarTecnicos();
          this.cerrarModal();
          Swal.fire('Éxito', 'Técnico editado con exito!', 'success');
        } else {
          console.log('Tecnico error al guardar');
        }
      },error=>{
        console.log(<any>error)
      });
    }
  }

  confirmarEliminar(id){
    Swal.fire({
      title: '¿Está seguro?',
      text: "Se eliminará al técnico",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, eliminar!'
    }).then((result) => {
      if (result.value) {
        this.deleteTecnico(id);
      }
    });
  }

}
