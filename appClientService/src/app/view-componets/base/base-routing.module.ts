import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

//guard
import {LoginGuard} from '../../login.guard';

import { InicioComponent } from './inicio/inicio.component';
//componentes
import { PanelLayoutComponent } from './panel-layout/panel-layout.component';
import { NavClientComponent } from './nav-client/nav-client.component';
import { TableClientComponent } from './table-client/table-client.component';
import { AddCsvComponent } from './add-csv/add-csv.component';
import { TecnicosComponent } from './tecnicos/tecnicos.component';
import { FormTecnicoComponent } from './form-tecnico/form-tecnico.component';
import { ActividadesTecnicoComponent } from './actividades-tecnico/actividades-tecnico.component';
import { FooterComponent } from './footer/footer.component'; 
import { NotFoundComponent } from './not-found/not-found.component';
import { PerfilComponent } from './perfil/perfil.component';
import { AlertaDeleteComponent } from './alerta-delete/alerta-delete.component';
import { TableRecmanualComponent } from './table-recmanual/table-recmanual.component';
import { ChatComponent } from './chat/chat.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'Base'
    },
    children: [
      {
        path: '',
        redirectTo: 'inicio'
      },
      {
        path: 'inicio',
        component: PanelLayoutComponent,
        data: {
          title: 'Inicio'
        }
      },
      {
        path:'chat',
        component:ChatComponent,
        canActivate:[LoginGuard]
      },
      {
        path:'tecnicos',
        component:TecnicosComponent,
        canActivate:[LoginGuard]
      },
      {
        path:'perfil',
        component:PerfilComponent,
        canActivate:[LoginGuard]
      },
      {
        path: 'not-found',
        component: NotFoundComponent
      },
      {
        path: '**',
        redirectTo: 'not-found'
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
  providers: [
  ]
})
export class BaseRoutingModule {}
