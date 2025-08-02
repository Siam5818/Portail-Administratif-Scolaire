import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root',
})
export class BulletinService {
  private api_Url = 'http://127.0.0.1:8000/api/v1/bulletins';

  constructor(private httpclient: HttpClient) {}
}
