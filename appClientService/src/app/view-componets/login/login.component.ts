import { Component, OnInit } from '@angular/core';
import { Usuario } from '../../models/usuario';
import { InjectableAnimationEngine } from '@angular/platform-browser/animations/src/providers';
import { LoginService } from '../../services/login.service';
import { Tecnico } from '../../models/tecnico';
import { Router } from '@angular/router';
import { Command } from 'protractor';
import { COMMON_DEPRECATED_I18N_PIPES } from '@angular/common/src/pipes/deprecated';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {

  public username:string;
  public password:string;
  constructor(private loginService:LoginService,private router:Router) { }

  ngOnInit() {
  }


    logear(){
      if(!this.username){
        alert("Ingrese un email");
        return;
      }
      if(!this.password){
        alert("Ingrese su contraseña");
        return;
      }
      let usuario=new Usuario();
      usuario.username=this.username;
      usuario.password=this.password;
     
      this.loginService.autenticarUsuario(usuario).subscribe(
        result=>{
          if(result){
            localStorage.setItem("token",result.access_token);
            localStorage.setItem("token_type",result.token_type);
            this.loginService.getUserDataAutenticate().subscribe(
              res=>{
                if(res){
                  localStorage.setItem("nombre",res.name);
                  location.reload();
                }
              }
            ); 
          }
        },
        error=>{
          if(error){
            alert("Credenciales incorrectas");
          }
        }
      );
    }
}
