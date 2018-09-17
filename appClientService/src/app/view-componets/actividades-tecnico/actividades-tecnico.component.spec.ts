import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ActividadesTecnicoComponent } from './actividades-tecnico.component';

describe('ActividadesTecnicoComponent', () => {
  let component: ActividadesTecnicoComponent;
  let fixture: ComponentFixture<ActividadesTecnicoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ActividadesTecnicoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ActividadesTecnicoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
