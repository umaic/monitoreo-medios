import { Component, NgModule } from '@angular/core';
import { Http } from '@angular/http';
import { Router } from '@angular/router';
import { AuthHttp } from 'angular2-jwt';
import { Angular2Csv } from 'angular2-csv/Angular2-csv';


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
  states;
  defaultCat;
  defaultSrc;
  cats;
  cats1;
  cats2;
  sources;
  keys;
  height = 2;
  color = '#4092F1';
  runInterval = 300;
  options;
  constructor(public router: Router, public http: Http, public authHttp: AuthHttp) {
    this.jwt = localStorage.getItem('id_token');
    this.decodedJwt = this.jwt;// && window.jwt_decode(this.jwt);
	this.startDate = new Date(2017, 1, 4);
	this.cats1 = [{id: 0, name: 'CATEGORIAS'},
    { id: 1, name: 'Fondo de tierras'},
	{ id: 2, name: 'Otros mecanismos para promover el acceso a la tierra'},
	{ id: 3, name: 'Formalización masiva'},
	{ id: 4, name: 'Sistema General de Información Catastral, Integral y multipropósito'},
	{ id: 5, name: 'Cierre de la frontera agrícola, vocación de la tierra, ordenamiento territorial y protección ambiental'},
	{ id: 6, name: 'Zonas de Reserva Campesina (ZRC)'},
	{ id: 7, name: 'PDET y planes de acción para la transformación regional'},
    { id: 8, name: 'Infraestructura vial (Vias terciarias)'},
	{ id: 9, name: 'Infraestructura de riego'},
	{ id: 10, name: 'Infraestructura eléctrica y de conectividad'},
	{ id: 11, name: 'Desarrollo social: salud rural'},
	{ id: 12, name: 'Desarrollo social: educación rural'},
	{ id: 13, name: 'Estimulo a la producción agropecuaria y la economía solidaria y cooperativa.'},
	{ id: 14, name: 'Garantias de seguridad  y protección para el ejercicio de la política'},
	{ id: 15, name: 'Garantías y Promoción de la Participación Ciudadana para los movimientos y organizaciones sociales'},
	{ id: 16, name: 'Garantías para la movilización y la protesta social'},
	{ id: 17, name: 'Participación ciudadana a través de medios de comunicación comunitarios, institucionales y regionales'},
	{ id: 18, name: 'Garantías para la reconciliación, la convivencia, la tolerancia y la no estigmatización'},
	{ id: 19, name: 'Control y veeduría ciudadana'},
	{ id: 20, name: 'Política para el fortalecimiento de la planeación democrática y participativa'},
	{ id: 21, name: 'Pluralismo político: medidadas de acceso al sistema y la competencia política en condiciones de igualdad'},
	{ id: 22, name: 'Promoción de la participación electoral, reforma del regimen y la organización electoral.'},
	{ id: 23, name: 'Promoción de la transparencia procesos electorales y asignación de pauta oficial'},
	{ id: 24, name: 'Promoción de una cultura política democrática y participativa'},
	{ id: 25, name: 'Promoción de la representación política de poblaciones y zonas especialmente afectadas por el conflicto y el abandono '},
	{ id: 26, name: 'Reincorporación política'},
	{ id: 27, name: 'Reincorporación económica y social'},
	{ id: 28, name: 'Sistema Integral de Seguridad y Proteccion para el Ejercicio de la Política'},
	{ id: 29, name: 'Programa Integral de Seguridad y Proteccion para las comunidades'},
	{ id: 30, name: 'Medidas de prevención y lucha contra la corrupción'},
	{ id: 31, name: 'Acción Integral contra las Minas Antipersona'},
	{ id: 32, name: 'Construcción participativa y desarrollo de los planes integrales comunitarios y municipales de sustitución y desarrollo alternativo (PISDA)'},
	{ id: 33, name: 'Judicialización efectiva'},
	{ id: 34, name: 'Actos tempranos de reconocimiento de responsabilidad colectiva y acciones concretas de contribución a la reparación'},
	{ id: 35, name: 'Reparación Colectiva: Medidas de Reparación Integral para la Construcción de Paz'},];
	this.cats2 = [{id: 0, name: 'VIOLENCIA ARMADA'},
    { id: 36, name: 'Acciones Armadas'},
	{ id: 37, name: 'Ataques contra la población civil'},
	{ id: 38, name: 'Ataque a objetivos ilícitos de guerra'},
	{ id: 39, name: 'Uso de explosivos remanentes de guerra'},
	{ id: 40, name: 'Desplazamiento(Expulsión)'},
	{ id: 41, name: 'Categorías Complementarias'},
	{ id: 42, name: 'Restricción al acceso humanitario'},
    { id: 0, name: 'DESASTRES'},
	{ id: 50, name: 'Inundación'},
	{ id: 51, name: 'Contaminación'},
	{ id: 52, name: 'Varios'},
	{ id: 53, name: 'Movimientos de tierra'},
	{ id: 54, name: 'Clima'},
	{ id: 55, name: 'Fuego'},
	{ id: 56, name: 'Sectores'},
	{ id: 57, name: 'Alertas'},
	{ id: 58, name: 'Necesidades'},];
	this.sources = [{ id: 1, name: 'El Tiempo'},
	{ id: 2, name: 'Ejercito'},
	{ id: 3, name: 'BlueRadio'},
	{ id: 4, name: 'Caracol'},
	{ id: 5, name: 'Justicia y Paz'},
	{ id: 6, name: 'KapitalStereo'},
	{ id: 7, name: 'Cococauca'},
	{ id: 8, name: 'W Radio'},
	{ id: 9, name: 'La Patria'},
	{ id: 10, name: 'Diario del Sur'},
	{ id: 11, name: 'Radio Santa Fe'},
	{ id: 12, name: 'Buenaventura en Linea'},
	{ id: 13, name: 'CEDEMA'},
	{ id: 14, name: 'Diario Extra Casanare'},
	{ id: 15, name: 'CM&'},
	{ id: 16, name: 'Cotagio radio'},
	{ id: 17, name: 'Soacha Ilustrada'},
	{ id: 18, name: 'La Silla Vacia'},
	{ id: 19, name: 'Boyaca al dia'},
	{ id: 20, name: 'Contexto Ganadero'},
	{ id: 21, name: 'El Circulo'},
	{ id: 22, name: 'El Pueblo'},
	{ id: 23, name: 'Radio Super Popayan'},
	{ id: 24, name: 'El Tabloide'},
	{ id: 25, name: 'Goyes Noticias'},
	{ id: 26, name: 'Consejo Regional Indígena del Cauca'},
	{ id: 27, name: 'La FM'},
	{ id: 28, name: 'La Voz de Yopal'},
	{ id: 29, name: 'La Voz del rio Arauca'},
	{ id: 30, name: 'El Nuevo Herald'},
	{ id: 31, name: 'Telesur'},
	{ id: 32, name: 'Meridiano 70'},
	{ id: 33, name: 'Minuto 30'},
	{ id: 34, name: 'Panorama Araucano'},
	{ id: 35, name: 'Q Radio'},
	{ id: 36, name: 'Sara Stereo'},
	{ id: 37, name: 'Min Educacion'},
	{ id: 38, name: 'Naciones Unidas'},
	{ id: 39, name: 'WWF'},
	{ id: 40, name: 'Fiscalia'},
	{ id: 41, name: 'Diario Occidente'},
	{ id: 42, name: 'DNP'},
	{ id: 43, name: 'El Caribe'},
	{ id: 44, name: 'El Universal'},
	{ id: 45, name: 'Porvenir'},
	{ id: 46, name: 'Tu Caqueta'},
	{ id: 47, name: 'ONU Misión Colombia'},
	{ id: 48, name: 'Policia'},
	{ id: 49, name: 'Canal TRO'},
	{ id: 50, name: 'Tele Pacifico'},
	{ id: 51, name: 'El Pilon'},
	{ id: 52, name: 'Capital Radio'},
	{ id: 53, name: 'Uni Panamericana'},
	{ id: 54, name: 'Periodico virtual'},
	{ id: 55, name: 'Q Hubo'},
	{ id: 56, name: 'El Diario del Norte'},
	{ id: 57, name: 'La Guajira Hoy'},
	{ id: 58, name: 'Mi Putumayo'},
	{ id: 59, name: 'Noti Frontera'},
	{ id: 60, name: 'Radio Macondo'},];
	this.Load2();
    this.defaultCat = 0;
	this.defaultSrc = 0;
	this.keys='';
	this.options = {
    fieldSeparator: '|',
    quoteStrings: '',
    //decimalseparator: ',',
    showLabels: false,
    showTitle: false,
    //useBom: true
  };
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
	 let url='/medios/backend/search.php?var='+this.wordQuery+'&cat='+this.defaultCat+'&src='+this.defaultSrc;
		this.http.get(url)
            .subscribe((data)=> {
                setTimeout(()=> {
					let data1=data.json();
					this.keys=data1.key;
					this.data=data1.resp;
					}, 1000);
            });
  }
Load2() {
    if(localStorage.getItem('user_id')==='1') {
    this.cats=this.cats1;
	} else {
    this.cats=this.cats2;
	}
  }
Load() {
	 
	 this.http.get(url)
            .subscribe((data)=> {
                setTimeout(()=> {
					let data1=data.json();
					console.log('cats:' + data1.resp);
					this.cats=data1.resp;
					}, 1000);
            });
  }

 down_csv() {
	new Angular2Csv(this.data, 'My Report',this.options);
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
