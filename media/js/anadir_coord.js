var map ; //Mapa global ;)
// RANDY 25/07/2014 Agregar mapa si está conectado
if(typeof google != 'undefined') {
    google.load("maps", "2");
    google.load("elements", "1", {
        packages : ["localsearch"]
    });
}
//google.load("maps", "2");
//google.load("elements", "1", {
//  packages : ["localsearch"]
//});
/*
PARA GOOGLE MAPS
*/
function IniciarMapa(longitud, latitud,crear_marca)
{
	
  if (GBrowserIsCompatible()) {
  map = new google.maps.Map2(document.getElementById('elmapa'));
  map.setMapType(G_PHYSICAL_MAP);
  //Longitud, latitud!!
  map.setCenter(new google.maps.LatLng(longitud, latitud), 6);
  var options = {
    zoomLimit: 16
  };  
  var lsc = new google.elements.LocalSearch(options);
  map.addControl(lsc);
  map.setMapType(G_HYBRID_MAP);
          map.addControl(new GSmallMapControl());
          map.addControl(new GMapTypeControl());
  GEvent.addListener(map, 'click', function(capa, punto) {
   if (!capa) { //si es una capa no hace nada
     if (punto)  {
      map.clearOverlays();
      anadirPuntoAlMapa( punto , map.getZoom(),"Latitud , Longitud <br>" + map.fromLatLngToDivPixel(punto) +
    	'<br>X:'+punto.x+' Y:'+punto.y,true);
      document.frmEditar.lng.value = punto.y;
      document.frmEditar.lat.value = punto.x;
     }
    }
  });
  if (crear_marca){
  	anadirPuntoAlMapa(new GPoint(latitud,longitud),16,"Longitud:"+longitud+"<br/> Latitud: "+latitud,true);
  	document.frmEditar.lng.value = longitud;
    document.frmEditar.lat.value = latitud;
  }
  if (typeof(zonas_js) != "undefined")
  if (zonas_js.length>0){
  	for (i=0; i<zonas_js.length; i++) {
  	 anadirPuntoAlMapa(
  	  //lat,lng,nombre,provincia
  	  new GPoint(zonas_js[i][0],zonas_js[i][1]),16,
  	  	'<a href="javascript:desplegar('+zonas_js[i][0]+','+zonas_js[i][1]+',\''+
  	  	zonas_js[i][3]+'\')">'+zonas_js[i][2]+"</a>",false
  	 );
  	}
  }
 }
}

function desplegar(lat,lng,provincia){
 map.setCenter(new GLatLng(lng, lat), 13);
 p = document.getElementById('provincia');
 if (p){
  n = p.length;
  for (i=0; i<n; i++){
   if (provincia==p[i].value){
   	p[i].selected = true;
   	break;
   }
  }
 }
}

function anadirPuntoAlMapa( punto, zoom ,html, mostrar)
 {
	//map.centerAndZoom( punto, zoom) ;
	
	var etiqueta = new createMarker(punto, html);		
    map.addOverlay(etiqueta);
    if (mostrar){
     etiqueta.openInfoWindowHtml(html)
     map.setCenter(new GLatLng(punto.y, punto.x), zoom);
    }
}

function reiniciar(){ //Usar map.clearOverlays() 
 GUnload();
 IniciarMapa();
}

function createInfoMarker(point, address) {
	var marker = new GMarker(point);
	GEvent.addListener(marker, "click", function() { marker.openInfoWindowHtml(address); } );
	return marker;
}

function createMarker(point, html) {
 var marker = new GMarker(point);
 GEvent.addListener(marker, "click", function()
 {
 marker.openInfoWindowHtml(html);
 });
 return marker;
}

