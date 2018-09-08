import { TestBed, inject } from '@angular/core/testing';

import { OrdenService } from './orden.service';

describe('OrdenService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [OrdenService]
    });
  });

  it('should be created', inject([OrdenService], (service: OrdenService) => {
    expect(service).toBeTruthy();
  }));
});
