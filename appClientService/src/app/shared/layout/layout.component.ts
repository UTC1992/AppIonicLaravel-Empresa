import {Component, OnDestroy} from '@angular/core';

import { Router } from '@angular/router';
import { BreakpointObserver, Breakpoints, BreakpointState } from '@angular/cdk/layout';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';

import { LoginService } from '../../services/login.service';
//import swal from 'sweetalert2';

@Component({
  selector: 'app-dashboard',
  templateUrl: './layout.component.html',
  styleUrls: ['./layout.component.css']
})
export class LayoutComponent {

  isHandset$: Observable<boolean> = this.breakpointObserver.observe(Breakpoints.Handset)
  .pipe(
  map(result => result.matches)
  );
  
  usuario: any;
  
  constructor(
    private breakpointObserver: BreakpointObserver,
    public loginService: LoginService,
    private router:Router
    ) {}

    ngOnInit(): void {
      this.usuario = localStorage.getItem("nombre");
    }

    logout(): void{
      this.loginService.logout();
      //swal('Logout', `Hola ${username}, has cerrado sesión con éxito!`, 'success');
      this.router.navigate(['/login']);
    }

  /*
  constructor(
    public loginService: LoginService,
    private router:Router
  ){
    
  }

  
  logout(): void{
    let username = this.loginService.usuario.username;
    this.loginService.logout();
    swal('Logout', `Hola ${username}, has cerrado sesión con éxito!`, 'success');
    this.router.navigate(['/login']);
  }
  */

}
