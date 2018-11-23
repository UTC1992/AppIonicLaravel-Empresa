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
import { LoginService } from './services/login.service';

import { Routes,RouterModule } from '@angular/router';
import { WelcomeComponent } from './view-componets/welcome/welcome.component';
import { TecnicosComponent } from './view-componets/tecnicos/tecnicos.component';
import { FormTecnicoComponent } from './view-componets/form-tecnico/form-tecnico.component';
import { ActividadesTecnicoComponent } from './view-componets/actividades-tecnico/actividades-tecnico.component';
import { Ng2SearchPipeModule } from 'ng2-search-filter'; //importing the module
import { Ng2OrderModule } from 'ng2-order-pipe';
import {NgxPaginationModule} from 'ngx-pagination';
import { LoginComponent } from './view-componets/login/login.component';

import { FooterComponent } from './view-componets/footer/footer.component'; 
import {LoginGuard} from './login.guard';
import {NoLoginGuard} from './no-login.guard';

import { NgxSpinnerModule } from 'ngx-spinner';
import { NotFoundComponent } from './view-componets/not-found/not-found.component';

import { MultiselectDropdownModule } from 'angular-2-dropdown-multiselect';

const appRoutes: Routes = [
  { path: '',
    redirectTo: '/login',
    pathMatch: 'full',
    canActivate:[NoLoginGuard]
   
  },
  { path: 'login',
    component:LoginComponent,
    canActivate:[NoLoginGuard]
    
  },
  { path: 'panel-layout',
    component:PanelLayoutComponent,
    canActivate:[LoginGuard]
  },
  {
    path:'tecnicos',
    component:TecnicosComponent,
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
  
];

@NgModule({
  declarations: [
    AppComponent,
    PanelLayoutComponent,
    NavClientComponent,
    TableClientComponent,
    AddCsvComponent,
    WelcomeComponent,
    TecnicosComponent,
    FormTecnicoComponent,
    ActividadesTecnicoComponent,
    LoginComponent,
    FooterComponent,
    NotFoundComponent,
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
    NgxPaginationModule,
    NgxSpinnerModule,
    MultiselectDropdownModule,
  ],
  providers: [OrdenService,TecnicoService,LoginService,LoginGuard,NoLoginGuard],
  bootstrap: [AppComponent]
})
export class AppModule { }
