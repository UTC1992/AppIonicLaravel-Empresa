import { Component, OnInit } from '@angular/core';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import { PerfilService } from '../../../services/perfil.service';
import { LoginService } from '../../../services/login.service';
import { Usuario } from '../../../models/usuario';

@Component({
  selector: 'app-perfil',
  templateUrl: './perfil.component.html',
  styleUrls: ['./perfil.component.css']
})
export class PerfilComponent implements OnInit {
  form_empresa_edicion: FormGroup;
  form_nombre_edicion: FormGroup;
  form_password_edicion: FormGroup;
  form_email_edicion: FormGroup;

  nombre_usuario:string;
  empresa:string;
  email:string;
  constructor(
          private perfilService:PerfilService,
          private loginServie:LoginService,
          private formBuilder: FormBuilder,
  ) { 
    this.nombre_usuario=localStorage.getItem("nombre");
    this.empresa=localStorage.getItem("empresa");
    this.email=localStorage.getItem("email");
    this.createForms();
  }

  ngOnInit() {
  }

  createForms(){
    this.form_empresa_edicion = this.formBuilder.group({
      empresa: ['', Validators.required],
    });
    this.form_nombre_edicion = this.formBuilder.group({
      nombre: ['', Validators.required],
    });
    this.form_email_edicion = this.formBuilder.group({
      email: ['', Validators.required],
    });
    this.form_password_edicion = this.formBuilder.group({
      password: ['', Validators.required],
    });
  }
  openModalEmpresa(){
    this.form_empresa_edicion = this.formBuilder.group({
      empresa: [localStorage.getItem("empresa"), Validators.required],
    });
  }

  onSubmitEditEmpresa(){
    let empresa=this.form_empresa_edicion.get('empresa').value;
    if(empresa!=""){
      let input = new FormData();
    input.append('empresa', empresa);
    this.perfilService.editarEmpresa(input)
    .subscribe(
      res=>{
        if(res){
          this.loginServie.getUserDataAutenticate().subscribe(
            res_us=>{
              if(res_us){
                localStorage.removeItem("empresa");
                localStorage.setItem("empresa",res_us.empresa);
                this.empresa=localStorage.getItem("empresa");
                location.reload();
              }
            }
          );
        }
      }
    );
    }else{
      alert("Ingrese nombre de empresa");
      return;
    }
    
  }


  // editar nombre
  openModalNombre(){
    this.form_nombre_edicion = this.formBuilder.group({
      nombre: [localStorage.getItem("nombre"), Validators.required],
    });
  }
  onSubmitEditNombre(){
    let nombre=this.form_nombre_edicion.get('nombre').value;
    if(nombre!=""){
      let input = new FormData();
    input.append('nombre', nombre);
    this.perfilService.editarNombre(input)
    .subscribe(
      res=>{
        if(res){
          this.loginServie.getUserDataAutenticate().subscribe(
            res_us=>{
              if(res_us){
                localStorage.removeItem("nombre");
                localStorage.setItem("nombre",res_us.name);
                this.empresa=localStorage.getItem("nombre");
                location.reload();
              }
            }
          );
        }
      }
    );
    }else{
      alert("Ingrese un nombre");
      return;
    }
    
  }

  // editar email
  openModalEmail(){
    this.form_email_edicion = this.formBuilder.group({
      email: [localStorage.getItem("email"), Validators.required],
    });
  }

  onSubmitEditEmail(){
    let email=this.form_email_edicion.get('email').value;
    if(email!=""){
      let input = new FormData();
    input.append('email', email);
    this.perfilService.editarEmail(input)
    .subscribe(
      res=>{
        if(res){
          this.loginServie.getUserDataAutenticate().subscribe(
            res_us=>{
              if(res_us){
                localStorage.removeItem("email");
                localStorage.setItem("email",res_us.username);
                this.email=localStorage.getItem("email");
                location.reload();
              }
            }
          );
        }
      }
    );
    }else{
      alert("Ingrese un email");
      return;
    }

    
  }
  // editar password

  openModalPassword(){
    this.form_password_edicion = this.formBuilder.group({
      password: ['***********', Validators.required],
    });
  }

  onSubmitEditPassword(){
    let pass=this.form_password_edicion.get('password').value;
    if(pass!=""){
      let input = new FormData();
      input.append('password',pass );
      this.perfilService.editarPassword(input)
      .subscribe(
        res=>{
          if(res){
            this.CerrarSesion();
          }
        }
      );
    }else{
      alert("Ingrese una contraseña");
      return;
    }
   
  }

  CerrarSesion(){
    localStorage.removeItem("empresa");
    localStorage.removeItem("email");
    localStorage.removeItem("nombre");
    localStorage.removeItem("token");
    localStorage.removeItem("token_type");
    location.reload();
  }
}
