// Angular
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgModule, NO_ERRORS_SCHEMA, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { HttpModule } from '@angular/http';

//ngx
import { ModalModule, BsModalRef } from 'ngx-bootstrap/modal';
import { NgxSpinnerModule } from 'ngx-spinner';
import {NgxPaginationModule} from 'ngx-pagination';
import { MatTableModule, MatToolbarModule, MatButtonModule, 
  MatSidenavModule, MatIconModule, MatListModule, 
  MatGridListModule, MatCardModule, MatMenuModule, 
  MatTabsModule, MatDatepickerModule, MatNativeDateModule, MatTableDataSource } from '@angular/material';
import {MatExpansionModule} from '@angular/material/expansion';
import { Ng2SearchPipeModule } from 'ng2-search-filter'; //importing the module
import { Ng2OrderModule } from 'ng2-order-pipe';
import { MultiselectDropdownModule } from 'angular-2-dropdown-multiselect';
import {MatDialogModule, MatFormFieldModule} from "@angular/material";

// Components Routing
import { BaseRoutingModule } from './base-routing.module';
import { MatAutocompleteModule } from '@angular/material/autocomplete';
import { MatInputModule } from '@angular/material/input';
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

//MB bootstrap
import { MDBBootstrapModule } from 'angular-bootstrap-md';
import { LecturasComponent } from './lecturas/lecturas.component';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    ModalModule.forRoot(),
    MDBBootstrapModule.forRoot(),
    BaseRoutingModule,
    ReactiveFormsModule,
    MatAutocompleteModule,
    MatInputModule,
    MatFormFieldModule,
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
    MatDialogModule,
    MatFormFieldModule,
    MatExpansionModule,
    MatTabsModule,
    MatDatepickerModule,
    MatNativeDateModule,
  ],
  declarations: [
    InicioComponent,
    PanelLayoutComponent,
    NavClientComponent,
    TableClientComponent,
    AddCsvComponent,
    TecnicosComponent,
    FormTecnicoComponent,
    ActividadesTecnicoComponent,
    FooterComponent,
    NotFoundComponent,
    PerfilComponent,
    AlertaDeleteComponent,
    TableRecmanualComponent,
    ChatComponent,
    LecturasComponent,
    
  ],
  exports: [
    MDBBootstrapModule,
  ],
  providers: [
  ],
  entryComponents: [
    AlertaDeleteComponent
  ],
  schemas: [
    NO_ERRORS_SCHEMA, 
    CUSTOM_ELEMENTS_SCHEMA
  ]
  
})
export class BaseModule { }