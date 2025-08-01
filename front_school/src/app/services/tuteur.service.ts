import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root',
})
export class TuteurService {
  api_Url = 'http://127.0.0.1:8000/api/v1';

  constructor(private httpclient: HttpClient) {}
}
