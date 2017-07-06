import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { Http } from '@angular/http';
import { contentHeaders } from '../common/headers';

const styles   = require('./login.css');
const template = require('./login.html');

@Component({
  selector: 'login',
  template: template,
  styles: [ styles ]
})

export class Login {
  constructor(public router: Router, public http: Http) {
  }

  login(event, username, password) {
   // event.preventDefault();
    let body = JSON.stringify({ username, password });
    	   console.log('Success str' + body );
	    let url='http://localhost:8888/v2/backend/login.php?var='+body;

    this.http.get(url)
	.subscribe((data)=> {
                setTimeout(()=> {
					let data1=data.json();
				    localStorage.setItem('id_token', data1.token);
                    this.router.navigate(['home']);
					}, 1000);
            });

  }

  signup(event) {
    event.preventDefault();
    this.router.navigate(['signup']);
  }
}
