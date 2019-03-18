import { BrowserModule } from '@angular/platform-browser';
import { NgModule, ModuleWithProviders } from '@angular/core';
import { HttpModule } from '@angular/http';
import { FormsModule,ReactiveFormsModule } from '@angular/forms';

import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { LayoutModule } from '@angular/cdk/layout';
import { MatTableModule, MatToolbarModule, MatButtonModule, MatSidenavModule, MatIconModule, MatListModule, MatGridListModule, MatCardModule, MatMenuModule } from '@angular/material';

import { OrdenService } from './services/orden.service';
import { TecnicoService } from './services/tecnico.service';
import { LoginService } from './services/login.service';
import { Routes,RouterModule } from '@angular/router';
import { WelcomeComponent } from './view-componets/welcome/welcome.component';

import { Ng2SearchPipeModule } from 'ng2-search-filter'; //importing the module
import { Ng2OrderModule } from 'ng2-order-pipe';
import {NgxPaginationModule} from 'ngx-pagination';

import { NgxSpinnerModule } from 'ngx-spinner';

import { MultiselectDropdownModule } from 'angular-2-dropdown-multiselect';

import {MatDialogModule, MatFormFieldModule} from "@angular/material";

import { AppRoutingModule } from './app-routing.module';

import {
  SharedModule,
  LayoutComponent
} from './shared';

const rootRouting: ModuleWithProviders = RouterModule.forRoot([], { useHash: true });

import { LoginComponent } from './view-componets/login/login.component';


@NgModule({
  declarations: [
    AppComponent,
    LayoutComponent,
    WelcomeComponent,
    LoginComponent
  ],
  imports: [
    AppRoutingModule,
    BrowserModule,
    BrowserAnimationsModule,
    rootRouting,
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
    MatDialogModule,
    MatFormFieldModule,
    SharedModule
  ],
  providers: [
  ],
  bootstrap: [AppComponent],
  
})
export class AppModule { }
