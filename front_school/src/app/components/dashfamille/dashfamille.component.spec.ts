import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DashfamilleComponent } from './dashfamille.component';

describe('DashfamilleComponent', () => {
  let component: DashfamilleComponent;
  let fixture: ComponentFixture<DashfamilleComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [DashfamilleComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(DashfamilleComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
