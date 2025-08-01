import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DashprofComponent } from './dashprof.component';

describe('DashprofComponent', () => {
  let component: DashprofComponent;
  let fixture: ComponentFixture<DashprofComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [DashprofComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(DashprofComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
