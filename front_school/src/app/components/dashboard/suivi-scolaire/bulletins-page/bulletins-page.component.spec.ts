import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BulletinsPageComponent } from './bulletins-page.component';

describe('BulletinsPageComponent', () => {
  let component: BulletinsPageComponent;
  let fixture: ComponentFixture<BulletinsPageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [BulletinsPageComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(BulletinsPageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
