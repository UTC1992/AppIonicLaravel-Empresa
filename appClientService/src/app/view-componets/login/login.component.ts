import { Component, OnInit } from '@angular/core';
import { Usuario } from '../../models/usuario';
import { LoginService } from '../../services/login.service';
import { Router } from '@angular/router';


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
                  localStorage.setItem("empresa",res.empresa);
                  localStorage.setItem("email",res.username);
                  localStorage.setItem("id_emp",res.id_emp);
                  //console.log("respuesta "+res.username);
                  this.router.navigate(['/base']);
                }else{
                  alert("Su cuenta se encuentra bloqueada, comuníquese con soporte técnico");
                  this.CerrarSesion();
                }
              }
            );
          }
        },  
        error=>{
          console.log(error);
        }
      );
    }
    CerrarSesion(){
      localStorage.removeItem("empresa");
      localStorage.removeItem("email");
      localStorage.removeItem("nombre");
      localStorage.removeItem("token");
      localStorage.removeItem("id_emp");
      localStorage.removeItem("token_type");
      location.reload();
      }
}
