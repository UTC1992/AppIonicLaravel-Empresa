import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TableRecmanualComponent } from './table-recmanual.component';

describe('TableRecmanualComponent', () => {
  let component: TableRecmanualComponent;
  let fixture: ComponentFixture<TableRecmanualComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TableRecmanualComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TableRecmanualComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
