import { BrowserModule } from '@angular/platform-browser';
import { NgModule, ModuleWithProviders, NO_ERRORS_SCHEMA, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { FormsModule,ReactiveFormsModule } from '@angular/forms';

import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { LayoutModule } from '@angular/cdk/layout';
import { MatTableModule, MatToolbarModule, 
  MatButtonModule, MatSidenavModule, MatIconModule, 
  MatListModule, MatGridListModule, MatCardModule, 
  MatMenuModule, MatDatepickerModule, MatNativeDateModule, 
  MatTableDataSource, MatSelectModule } from '@angular/material';
import {MatExpansionModule} from '@angular/material/expansion';
import {MatPaginatorModule} from '@angular/material/paginator';
import {MatTooltipModule} from '@angular/material/tooltip';

import { Routes,RouterModule } from '@angular/router';
import { WelcomeComponent } from './view-componets/welcome/welcome.component';

import { Ng2SearchPipeModule } from 'ng2-search-filter'; //importing the module
import { Ng2OrderModule } from 'ng2-order-pipe';
import {NgxPaginationModule} from 'ngx-pagination';

import {MatDialogModule, MatFormFieldModule} from "@angular/material";
import { AppRoutingModule } from './app-routing.module';

import {
  SharedModule,
  LayoutComponent
} from './shared';

const rootRouting: ModuleWithProviders = RouterModule.forRoot([], { useHash: true });
import { ModalModule, BsModalRef } from 'ngx-bootstrap/modal';

import { LoginComponent } from './view-componets/login/login.component';

//INTERCEPTORS
import { HttpClient, HttpHeaders, HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { TokenInterceptor } from './interceptors/token.interceptor';

//datapiker
import {MAT_MOMENT_DATE_ADAPTER_OPTIONS} from '@angular/material-moment-adapter';

@NgModule({
  declarations: [
    AppComponent,
    LayoutComponent,
    WelcomeComponent,
    LoginComponent,
  ],
  imports: [
    AppRoutingModule,
    BrowserModule,
    BrowserAnimationsModule,
    rootRouting,
    ModalModule.forRoot(),
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
    HttpClientModule,
    FormsModule,
    ReactiveFormsModule,
    Ng2SearchPipeModule,
    Ng2OrderModule,
    NgxPaginationModule,
    MatDialogModule,
    MatFormFieldModule,
    SharedModule,
    MatExpansionModule,
    MatDatepickerModule,
    MatNativeDateModule,
    MatPaginatorModule,
    MatSelectModule,
    MatTooltipModule
  ],
  providers: [
    MatDatepickerModule,
    MatNativeDateModule,
    BsModalRef,
    HttpClient,
    { provide: HTTP_INTERCEPTORS, useClass: TokenInterceptor, multi: true },
    { provide: MAT_MOMENT_DATE_ADAPTER_OPTIONS, useValue: { useUtc: true } }
  ],
  bootstrap: [AppComponent],
  schemas: [ NO_ERRORS_SCHEMA, CUSTOM_ELEMENTS_SCHEMA ]
  
})
export class AppModule { }
