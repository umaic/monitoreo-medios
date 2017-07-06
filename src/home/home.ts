import { Component, NgModule } from '@angular/core';
import { Http } from '@angular/http';
import { Router } from '@angular/router';
import { AuthHttp } from 'angular2-jwt';



const styles = require('./home.css');
const template = require('./home.html');

@Component({
  selector: 'home',
  template: template,
  styles: [ styles ]
})


export class Home {
  data;
  filterQuery = '';
  wordQuery = '';
  jwt: string;
  decodedJwt: string;
  response: string;
  api: string;

  constructor(public router: Router, public http: Http, public authHttp: AuthHttp) {
    this.jwt = localStorage.getItem('id_token');
    this.decodedJwt = this.jwt;// && window.jwt_decode(this.jwt);
  }

 Search() {
	 console.log('Success str' + this.wordQuery );
     let url='http://localhost:8888/v2/backend/search.php?var='+this.wordQuery;
	 this.http.get(url)
            .subscribe((data)=> {
                setTimeout(()=> {
					let data1=data.json();
					this.data=data1.resp[0].return_sql.result_sql[0].resp;
                }, 1000);
            });
  }

  logout() {
    localStorage.removeItem('id_token');
    this.router.navigate(['login']);
  }

  callAnonymousApi() {
    this._callApi('Anonymous', 'http://localhost:3001/api/random-quote');
  }

  callSecuredApi() {
    this._callApi('Secured', 'http://localhost:3001/api/protected/random-quote');
  }

  _callApi(type, url) {
    this.response = null;
    if (type === 'Anonymous') {
      // For non-protected routes, just use Http
      this.http.get(url)
        .subscribe(
          response => this.response = response.text(),
          error => this.response = error.text()
        );
    }
    if (type === 'Secured') {
      // For protected routes, use AuthHttp
      this.authHttp.get(url)
        .subscribe(
          response => this.response = response.text(),
          error => this.response = error.text()
        );
    }
  }
}
