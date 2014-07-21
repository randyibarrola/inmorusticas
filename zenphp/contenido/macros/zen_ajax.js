/**
* zen_ajax.js
* @author Juan Belon
* @access public
* @copyright LGPL, GPL
* @package zenphp
* @version 0.1.1
* @uses zenphp FrameWork
* @link http://csl2-zenphp.forja.rediris.es
* @link http://www.zenphp.es
* @link https://forja.rediris.es/projects/csl2-zenphp/
* @uses zen_andamio,zen_ajax
* @see DOM_XML_PHP
*/
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
/**
*  función para crear el evento onclick como truco
*  para comprobar si el navegador soporta ajax y actuar en consecuencia
*/
function zen_Inicializar() {
    //window.alert("Estoy analizando");
    lista =  document.getElementsByTagName("a");
    for(i=0; i < lista.length; i++) {
        lista[i].onclick = function (e) {
            url = this.href;
            p   = analizar_url(url);
            //window.alert(p);
            ajax = this.getAttribute('ajax');
            if ( !ajax || (ajax && ajax==0) ) {
            	//Si se dice que la relacion es de tipo no-ajax fuera, no se procesa
				return true;
            }
            anima1 = this.getAttribute('animacion_descarga');
            anima2 = this.getAttribute('animacion_carga');
            divisi = this.getAttribute('division');
            d = $(divisi);
            
            if (d && anima1){
            	try {
            		d.visualEffect(anima1);           		
            		d.innerHTML = '<img src="../zenphp/contenido/img/samsara.gif">';
            	} catch (e){
            		//alert(anima1);
            		throw "No se pudo aplicar la animacion "+this.animacion_descarga;
            	}
            }
            actual = analizar_url(document.location.href);
            if (p.protocolo != actual.protocolo) return true;
            if (p.protocolo != "file" && p.host != actual.host) return true;
            if (p.puerto    != actual.puerto) return true;
            /* peticion */
            zen_obtenerPagina( url ,d, anima2);
            return false;
        };
    }
}
var super_ego;
var objeto;
/**
* Descargar la pagina:
*/
function zen_obtenerPagina( url, ego, efecto_carga ) {
    var zen_pagina_ok  = function(t) {
        /* intenta analizar la pagina */
        try {
            elem = eval ( "(" + t.responseText  +")");
            if (ego && efecto_carga){
            	//Esperar a que termine otra animación...
            	ego.style.display = "block";
            	super_ego = ego;
            	objeto = elem;
            	setTimeout('super_ego.visualEffect("'+efecto_carga+'")',1000);
            	setTimeout('zen_DibujarPaginaContenido()',900);
            	setTimeout('zen_Inicializar()',1100);
            } else {
            	zen_DibujarPagina( elem );
            	zen_Inicializar();
            }
            
        } catch(e) {
            /* Error: usar metodo normal*/
            //zen_PeticionNormal( url );
            throw "Error en las operaciones...";
        }
    }
    var zen_error_ajax  = function(t){
        zen_PeticionNormal(url);
    }
    var zen_peticion_ajax = new Ajax.Request(url,
    {
        method:'post',  onSuccess:zen_pagina_ok, onFailure:zen_error_ajax, parameters: "zen_ajax=1"
    }
    )
}

function zen_DibujarPaginaContenido(){
	zen_DibujarPagina(objeto);
}
function zen_DibujarPagina( obj ) {
    try {
        elemento = $(  obj.destino );        
        elemento.innerHTML = obj.contenido;
    } catch(e) {        
        //window.alert("ERROR Dibujando la pagina");
        throw "Error Dibujando la Pagina (" + e + ")";
    }
    try {
    	eval( obj.antes );
    } catch (e){
    	window.alert("ERROR Evaluando"+obj.antes);
    	//throw "Error evaluando las instrucciones del 'antes' de "+obj.name;
    }
    try {
    	//window.alert("ERROR Evaluando"+obj.despues);
    	
    	eval( obj.despues );
    	
    } catch (e){
    	window.alert("Error evaluando las sentencias despues");
    	//throw "Error evaluando las instrucciones del 'despues' de "+obj.name;
    }
    
    try {
    	programas = elemento.getElementsByTagName('script');
    	n = programas.length;
        for (i=0; i<n; i++)
         eval(programas[i].innerHTML);
    } catch (e){
    	window.alert("Error evaluando las sentencias script del documento cargado..."+programas[i].innerHTML);
    	//throw "error <script> erroneos";
    }
}
function zen_PeticionNormal(url) {
    document.location.href = url;
    //window.alert("no hay mas remedio, url");
}
/**
*  Añade un evento a la ventana actual
*  Se usa para generar el evento que cambia los enlaces
*  por peticiones Ajax si es que están soportados por
*  el navegador
*/
function zen_anadirEventoCarga(func) {
    var oldonload = window.onload;
    if (typeof window.onload != 'function') {
        window.onload = func;
    } else {
        window.onload = function() {
            if (oldonload)
            oldonload();
            func();
        }
    }
}
function analizar_url(buffer) {
    var resultado = { };
    resultado.protocolo = "";
    resultado.usuario = "";
    resultado.password = "";
    resultado.host = "";
    resultado.puerto = "";
    resultado.ruta = "";
    resultado.peticion = "";
    seccion= "PROTOCOLO";
    inicio = 0;
    escape = false;
    while(inicio < buffer.length) {
        if(seccion == "PROTOCOLO") {
            if(buffer.charAt(inicio) == ':') {
                seccion = "DESPUES_PROTOCOLO";
                inicio++;
            } else if(buffer.charAt(inicio) == '/' && resultado.protocolo.length() == 0) {
                seccion = PATH;
            } else {
                resultado.protocolo += buffer.charAt(inicio++);
            }
        } else if(seccion == "DESPUES_PROTOCOLO") {
            if(buffer.charAt(inicio) == '/') {
                if(!escape) {
                    escape = true;
                } else {
                    escape  = false;
                    seccion = "USUARIO";
                }
                inicio ++;
            } else {
                throw new zen_guardar_excepcion("Protocolo shell debe separarse con 2 barras /");
            }
        } else if(seccion == "USUARIO") {
            if(buffer.charAt(inicio) == '/') {
                resultado.host = resultado.usuario;
                resultado.usuario = "";
                seccion = "RUTA";
            } else if(buffer.charAt(inicio) == '?') {
                resultado.host = resultado.usuario;
                resultado.usuario = "";
                seccion = "PETICION";
                inicio++;
            } else if(buffer.charAt(inicio) == ':') {
                seccion = "PASSWORD";
                inicio++;
            } else if(buffer.charAt(inicio) == '@') {
                seccion = "HOST";
                inicio++;
            } else {
                resultado.usuario += buffer.charAt(inicio++);
            }
        } else if(seccion == "PASSWORD") {
            if(buffer.charAt(inicio) == '/') {
                resultado.host = resultado.usuario;
                resultado.puerto = resultado.password;
                resultado.usuario = "";
                resultado.password = "";
                seccion = "RUTA";
            } else if(buffer.charAt(inicio) == '?') {
                resultado.host = resultado.usuario;
                resultado.puerto = resultado.password;
                resultado.usuario = "";
                resultado.password = "";
                seccion = "PETICION";
                inicio ++;
            } else if(buffer.charAt(inicio) == '@') {
                seccion = "HOST";
                inicio++;
            } else {
                resultado.password += buffer.charAt(inicio++);
            }
        } else if(seccion == "HOST") {
            if(buffer.charAt(inicio) == '/') {
                seccion = "RUTA";
            } else if(buffer.charAt(inicio) == ':') {
                seccion = "PUERTO";
                inicio++;
            } else if(buffer.charAt(inicio) == '?') {
                seccion = "PETICION";
                inicio++;
            } else {
                resultado.host += buffer.charAt(inicio++);
            }
        } else if(seccion == "PUERTO") {
            if(buffer.charAt(inicio) = '/') {
                seccion = "RUTA";
            } else if(buffer.charAt(inicio) == '?') {
                seccion = "PETICION";
                inicio++;
            } else {
                resultado.puerto += buffer.charAt(inicio++);
            }
        } else if(seccion == "RUTA") {
            if(buffer.charAt(inicio) == '?') {
                seccion = "PETICION";
                inicio ++;
            } else {
                resultado.ruta += buffer.charAt(inicio++);
            }
        } else if(seccion == "PETICION") {
            resultado.peticion += buffer.charAt(inicio++);
        }
    }
    if(seccion == "PROTOCOLO") {
        resultado.host = resultado.protocolo;
        resultado.protocolo = "http";
    } else if(seccion == "DESPUES_PROTOCOLO") {
        throw new zen_guardar_excepcion("Invalid url");
    } else if(seccion == "USUARIO") {
        resultado.host = resultado.usuario;
        resultado.usuario = "";
    } else if(seccion == "PASSWORD") {
        resultado.host = resultado.usuario;
        resultado.puerto = resultado.password;
        resultado.usuario = "";
        resultado.password = "";
    }
    return resultado;
}
/**
*    Analizar URL
*
*    Analizar una URL y devuelve un
*    objeto con sus partes
*/
function zen_guardar_excepcion(descripcion) {
    this.descripcion = descripcion;
    //window.alert(descripcion);
}
/**
* Comienza el evento!
*/
zen_anadirEventoCarga( zen_Inicializar );