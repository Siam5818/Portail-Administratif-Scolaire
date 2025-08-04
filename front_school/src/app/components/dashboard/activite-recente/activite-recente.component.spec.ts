import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ActiviteRecenteComponent } from './activite-recente.component';

describe('ActiviteRecenteComponent', () => {
  let component: ActiviteRecenteComponent;
  let fixture: ComponentFixture<ActiviteRecenteComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ActiviteRecenteComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(ActiviteRecenteComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
