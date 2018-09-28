import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpModule } from '@angular/http';
import { FormsModule,ReactiveFormsModule } from '@angular/forms';

import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { LayoutModule } from '@angular/cdk/layout';
import { MatTableModule, MatToolbarModule, MatButtonModule, MatSidenavModule, MatIconModule, MatListModule, MatGridListModule, MatCardModule, MatMenuModule } from '@angular/material';
import { PanelLayoutComponent } from './view-componets/panel-layout/panel-layout.component';
import { NavClientComponent } from './view-componets/nav-client/nav-client.component';
import { TableClientComponent } from './view-componets/table-client/table-client.component';
import { AddCsvComponent } from './view-componets/add-csv/add-csv.component';
import { OrdenService } from './services/orden.service';
import { TecnicoService } from './services/tecnico.service';
import { DistribucionComponent } from './view-componets/distribucion/distribucion.component';
import { Routes,RouterModule } from '@angular/router';
import { WelcomeComponent } from './view-componets/welcome/welcome.component';
import { AdminComponent } from './view-componets/admin/admin.component';
import { TecnicosComponent } from './view-componets/tecnicos/tecnicos.component';
import { FormTecnicoComponent } from './view-componets/form-tecnico/form-tecnico.component';
import { ActividadesTecnicoComponent } from './view-componets/actividades-tecnico/actividades-tecnico.component';
import { Ng2SearchPipeModule } from 'ng2-search-filter'; //importing the module
import { Ng2OrderModule } from 'ng2-order-pipe';
import {NgxPaginationModule} from 'ngx-pagination'; 

const appRoutes: Routes = [
  { path: '',
    component:WelcomeComponent,
    pathMatch: 'full'
  },
  { path: 'panel-layout',
    component:PanelLayoutComponent
  },
  { path: 'distribucion',
    component:DistribucionComponent
  },
  {
    path: 'admin',
    component: AdminComponent
  },
  {
    path:'tecnicos',
    component:TecnicosComponent
  }
];

@NgModule({
  declarations: [
    AppComponent,
    PanelLayoutComponent,
    NavClientComponent,
    TableClientComponent,
    AddCsvComponent,
    DistribucionComponent,
    WelcomeComponent,
    AdminComponent,
    TecnicosComponent,
    FormTecnicoComponent,
    ActividadesTecnicoComponent,
  ],
  imports: [
    RouterModule.forRoot(appRoutes,{enableTracing: true}),
    BrowserModule,
    BrowserAnimationsModule,
    LayoutModule,
    MatToolbarModule,
    MatButtonModule,
    MatSidenavModule,
    MatIconModule,
    MatListModule,
    MatGridListModule,
    MatCardModule,
    MatMenuModule,
    MatTableModule,
    HttpModule,
    FormsModule,
    ReactiveFormsModule,
    Ng2SearchPipeModule,
    Ng2OrderModule,
    NgxPaginationModule
  ],
  providers: [OrdenService,TecnicoService],
  bootstrap: [AppComponent]
})
export class AppModule { }
