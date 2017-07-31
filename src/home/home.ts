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
  startDate: any;
  data;
  filterQuery = '';
  wordQuery = '';
  jwt: string;
  decodedJwt: string;
  response: string;
  api: string;
  category;
  cate;

  constructor(public router: Router, public http: Http, public authHttp: AuthHttp) {
    this.jwt = localStorage.getItem('id_token');
    this.decodedJwt = this.jwt;// && window.jwt_decode(this.jwt);
	this.startDate = new Date(2017, 1, 4);
	this.cate=[{name:'test',id:'1'}];
  }
 set humanDate(e){
    e = e.split('-');
    let d = new Date(Date.UTC(e[0], e[1]-1, e[2]));
    this.startDate.setFullYear(d.getUTCFullYear(), d.getUTCMonth(), d.getUTCDate());
  }

  get humanDate(){
    return this.startDate.toISOString().substring(0, 10);
  }

 Search() {
	 console.log('Success str' + this.wordQuery );
     let url='http://localhost:8888/v2/backend/search.php?var='+this.wordQuery;
	 this.http.get(url)
            .subscribe((data)=> {
                setTimeout(()=> {
					let data1=data.json();
					console.log('str' + data1);
					this.data=data1;
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
