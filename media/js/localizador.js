if ( typeof (zoom) == "undefined"){
 var zoom = 6;
}
 if ( typeof (tipomarcas) == "undefined"){
            var tipomarcas = 'todoxml';
         }
if ( typeof (address) == "undefined"){
var address = null;
}
var mapa;
var geocoder;
var htmls;
var marcas; //Info para enlaces de la barra derecha
var ini = false;
var localizacion =  new GLatLng(40.41733308929806,-3.7000536918640137); //Madrid
function init() {
    if (GBrowserIsCompatible()) {
        mapa = new GMap2(document.getElementById("localizador"));
        
        if(typeof (centerLongitud) == "undefined"){
            if (address != null){
              geocoder = new GClientGeocoder();
              geocoder.getLocations(address, centerInMap)
             
            }else{
                mapa.setCenter(localizacion, zoom);
            }
        }else{
             localizacion =  new GLatLng(centerLatitud,centerLongitud);
             mapa.setCenter(localizacion, zoom);
        }
        listMarkers(tipomarcas);
        
        mapa.addControl(new GLargeMapControl())
        mapa.addControl(new GMapTypeControl())
        mapa.setZoom(zoom)
        mapa.enableScrollWheelZoom();
        //Clustering:
     /*   function myClusterClick(args) {
			cluster.defaultClickAction=function(){
				mapa.setCenter(args.clusterMarker.getLatLng(), mapa.getBoundsZoomLevel(args.clusterMarker.clusterGroupBounds))
				delete cluster.defaultClickAction;
			}
			var html='<div style="height:8em; overflow:auto; width:24em"><h4>'+args.clusteredMarkers.length+' Locations:</h4>';
			for (i=0; i<args.clusteredMarkers.length; i++) {
				html+='<a href="javascript:cluster.triggerClick('+args.clusteredMarkers[i].index+')">'+args.clusteredMarkers[i].getTitle()+'</a><br />';
			}
			html+='<br /><a href="javascript:void(0)" onclick="cluster.defaultClickAction()">Zoom</a> in to show these locations</div>';
			//	args.clusterMarker.openInfoWindowHtml(html);
			mapa.openInfoWindowHtml(args.clusterMarker.getLatLng(), html);
		}
		
		//	create a ClusterMarker
		cluster=new ClusterMarker(mapa, 
		{clusterMarkerTitle:'Click to see info about %count locations' , clusterMarkerClick:myClusterClick });
        */
        
        //Buscador
        var lsc = new google.maps.LocalSearch();
        mapa.addControl(lsc);
    }
}
function listMarkers(tipomarcas,z) {
	ini = z;
    mapa.clearOverlays();
    document.getElementById("propiedades").innerHTML = 
		"<table><tr><td colspan='4'><img src='/media/images/loader.gif'></td></tr></table>";
    new Ajax.Request(tipomarcas,{
    method: 'GET',
    onComplete: function(request){
        markers = eval("("+request.responseText+")");
        //alert(markers[2].marker.lat);
        
        var html2 ="";
        marcas   = new Array(markers.length);
        htmls    = new Array(markers.length);
        for (var i =0; i< markers.length; i++)
        {
            var marca = markers[i].marker
            var mlat = marca.lat;
            var mlng = marca.lng;
			
            if(mlat&&mlng){
                 latlng = new GLatLng(parseFloat(mlng),parseFloat(mlat));
               
                // Definimos el Icono
                var IconImage = new GIcon(G_DEFAULT_ICON);
                IconImage.image = "/media/images/iconos/"+ marca.tipo+".png";
                IconImage.iconSize = new GSize(20,34);
                 if(marca.imagen0){
                 	/*
                    var foto = marca.imagen0.split('/');
                    foto = foto[foto.length-1]    
                    */
                 	var foto = marca.imagen0;
                 }else{
                    var foto = null;
                 }
            
                //marker = addMarkerToMap(latlng,html)
                  var html='';
                  url = marca.seccion+'/'+marca.tipo+'/'+marca.id+"/"+marca.url_amiga;
                  if(foto){
                    html="<div style='min-height:160px; min-width:150px;'><h2>"+ marca.titulo+"</h2><br/>" +
                    "<a href='"+url+"'><img src='media/img/"+marca.seccion+
                    "/thumbs/"+foto+"' width='100px' ALIGN='left'/></a>"+
                    "<p>"+ marca.descripcion+"</p><br/>" +
                    "<p>"+'<a href="'+url+'">'+masinfo+"</a></p></div>";
                    /*html2+='<div class="prop-thumb"><img src="media/img/'+marca.seccion+'/thumbs/'+foto+
                    '" alt="'+marca.titulo+'"></div>';*/
                    //hayfoto = "/media/images/con_imagen.gif";
                    hayfoto = "<img alt='' width='45' src='media/img/"+marca.seccion+"/thumbs/"+foto+"'/>";
                   }else{
                    html= "<div style='min-height:160px; min-width:150px;'><h2>"+ marca.titulo+"</h2><br/>" +
                   // "<p>"+ marca.descripcion+"</p><br/>" +
                    "<p>"+'<a href="'+url+'">'+masinfo+"</a></p></div>";
                    hayfoto = "<img src='/media/images/sin_imagen.gif' alt=''>";
                   }
				   /*html2+='<h2><a title="'+marca.titulo+'" href="'+marca.seccion+'/'+marca.tipo+'/'+
				   marca.id+"/"+marca.url_amiga+'">'+marca.titulo+'</a></h2>'+
				   '<h3>'+marca.tipo+'<br>Precio : '+marca.precio+'</h3><div>'+
 				   marca.descripcion+'</div>'+*/
				   html2 += '<tr>'+
                   '<td><a href="javascript:centrarEn('+i+');">'+
                   //'<img alt="" src="/media/images/'+hayfoto+'"></a></th>'+
                   hayfoto+'</a></th>'+
                   '<td>'+marca.precio+'</th>'+
                   '<td>'+marca.habitaciones+'</td>'+
                   '<td>'+marca.provincia+'</td>'+
                   '<td>'+marca.fecha_alta+'</td>'+
                   '</tr>';
 				   
				  // '<div class="propdata"><div class="propdata-line"><div>Info...</div><div>9  Ba&ntilde;os</div></div>'+
				  // '<div class="propdata-line"><div>800Sup. descubierta en mts</div><div>800Sup. total en mts.</div>'+
				  // '<div>0.00Hectareas</div></div><div class="propdata-line propfeatures"><div>Piscina privada</div>'+
				 //  '</div>'+
				//  '</div></div>';
                var marker = CrearMarca(latlng,html,IconImage);
                //marker = addMarkerToMap(latlng,html)
                mapa.addOverlay(marker);
                mapa.closeInfoWindow();
            }
            marker.id  = marca.id;
            marcas[i]  = marker;
            htmls[i]   = html;
        }
        $("propiedades").innerHTML = '<table class="full"><caption>Propiedades</caption>'+
        		  '<thead><tr><th>Img</th><th>Prec.</th><th>Habi.</th><th>Prov.</th>'+
                  '<th>Alta</th></tr></thead><tbody>'+
                  html2+"</tbody></table>";
        html2="";
		localizacion =  new GLatLng(mlng,mlat);
		/*if (ini)
 		 mapa.setCenter(localizacion, 9);
 		else 
 		 ini = true;*/
 		if (centrar){
 		 mapa.setMapType(G_HYBRID_MAP);
 		 mapa.setCenter(centrar, 17);
 		 }
        }
    })
}
function centrarEn(i){
 sitio = marcas[i].getLatLng();
 mapa.setCenter(sitio, 17);
 marcas[i].openInfoWindowHtml(htmls[i]);
}
function CrearMarca(latlng, html,IconImage){
  var marker = new GMarker(latlng,IconImage)
   GEvent.addListener(marker, 'click', function(){
	marker.openInfoWindowHtml(html);
   });
   return marker;
}
window.onunload =GUnload;
// This function adds the point to the map
function addToMap(response)
{
           
    // Retrieve the object
    place = response.Placemark[0];
    // Retrieve the latitude and longitude
    point = new GLatLng(place.Point.coordinates[1],
    place.Point.coordinates[0]);
    // Center the map on this point
    mapa.setCenter(point, 13);
      
    mapa.openInfoWindowHtml(place.address);
}
// Para centrar el mapa en una dirección dada
function centerInMap(response)
{
    // Retrieve the object
    place = response.Placemark[0];
    // Retrieve the latitude and longitude
    localizacion = new GLatLng(place.Point.coordinates[1],
    place.Point.coordinates[0]);
 //alert(localizacion)
    // Center the map on this point
    mapa.setCenter(localizacion, 13);
}
