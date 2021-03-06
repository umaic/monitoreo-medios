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
  
    let body = JSON.stringify({ username, password });
    
	     let url='/medios/backend/login.php?username='+username+'&password='+password;
           console.log('url:' + url );
    this.http.get(url)
	.subscribe((data)=> {
                setTimeout(()=> {
					let data1=data.json();
				    localStorage.setItem('id_token', data1.token);
					localStorage.setItem('user_id', data1.id);
					if(data1.id>0) {
				        this.router.navigate(['home']);
					} else {
					   // this.router.navigate(['login']);
						}
					}, 1000);
            });

  }

  signup(event) {
    event.preventDefault();
    this.router.navigate(['signup']);
  }
}
