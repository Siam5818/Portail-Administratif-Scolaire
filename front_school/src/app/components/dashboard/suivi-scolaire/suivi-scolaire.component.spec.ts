import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SuiviScolaireComponent } from './suivi-scolaire.component';

describe('SuiviScolaireComponent', () => {
  let component: SuiviScolaireComponent;
  let fixture: ComponentFixture<SuiviScolaireComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [SuiviScolaireComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(SuiviScolaireComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
