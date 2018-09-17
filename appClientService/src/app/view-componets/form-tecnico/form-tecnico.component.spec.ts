import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormTecnicoComponent } from './form-tecnico.component';

describe('FormTecnicoComponent', () => {
  let component: FormTecnicoComponent;
  let fixture: ComponentFixture<FormTecnicoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormTecnicoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormTecnicoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
