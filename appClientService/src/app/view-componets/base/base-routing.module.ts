import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

//guard
import {LoginGuard} from '../../login.guard';

//componentes
import { PanelLayoutComponent } from './panel-layout/panel-layout.component';
import { TecnicosComponent } from './tecnicos/tecnicos.component'; 
import { NotFoundComponent } from './not-found/not-found.component';
import { PerfilComponent } from './perfil/perfil.component';
import { ChatComponent } from './chat/chat.component';
import { LecturasComponent } from './lecturas/lecturas.component';

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
        path:'lecturas',
        component: LecturasComponent,
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
