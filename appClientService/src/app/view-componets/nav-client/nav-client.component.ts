import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-nav-client',
  templateUrl: './nav-client.component.html',
  styleUrls: ['./nav-client.component.css']
})
export class NavClientComponent implements OnInit {

  isLogin:boolean;
  nombre_usuario:string;
  constructor() { 
    this.isLogin=false;
    if(localStorage.getItem("nombre")){
      this.isLogin=true;
      this.nombre_usuario=localStorage.getItem("nombre");
    }
  }

  ngOnInit() {
    
  }
  CerrarSesion(){
    localStorage.removeItem("nombre");
    localStorage.removeItem("token");
    localStorage.removeItem("token_type");
    location.reload();
  }
}
