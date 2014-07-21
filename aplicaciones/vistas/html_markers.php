<?php
class html_markers extends zen_html_modelo_datos {
 /**
  * @var markers
  */
 var $padre;
 /**
  * @var array
  */
 var $c;
 /**
  * @var zen_plantilla
  */
 var $p;
 /**
  * Constructor
  *
  * @param markers $padre
  * @return html_markers
  */
 function html_markers(&$padre){
  parent::zen_html_modelo_datos($padre);
  $this->p =& new zen_plantilla();
  $this->c =& $this->padre->padre->contenido;
 }
 function getxy($datos=null){
  if (!isset($datos[0])||!is_string($datos[0])) die("KO1");
  $xy = $this->padre->bd->obtener_fila_unica(
   $this->padre->bd->seleccion("lat,lng from zonas_js where lower(provincia)='".
   	strtolower(zen_sanar($datos[0]))."'")
  );
  if (!$xy) die("KO2");
  die(sprintf('[{"lat": %s, "lng": %s}]',$xy['lat'],$xy['lng']));
 }
 function u($datos){
  $where= "where c.publicado = 1";
  $sec = $campos = "";
  //Es destacados?
  //------------------------- DESTACADO ------------------------------------------------
  if (isset($datos[0]) && $datos[0]=="destacados"){
  	$where .= str_replace(",)",")"," and c.idc in (".
  	 $this->padre->bd->seleccion_unica("valor from configuracion where nombre='destacados'")
  	 .")"
  	);
  	array_shift($datos);
  }
  //------------------------- SECCION ---------------------------------------------------
  //#seccion#/#tipo#/#ciudad#/ --> /alquiler/casa/granada/precio || /todo/todo/todo :D por defecto
  //o bien : /#seccion#/#idc#/precio
  if (isset($_GET['s']) && is_string($_GET['s']) && array_key_exists($_GET['s'],$this->padre->secciones)){
   $sec = $_GET['s'];
   
   if (!empty($sec)){
  	 $where .= " and seccion='$sec'";
   }
   //-------- CATEGORIA -------------------------
   if (isset($_GET['c']) && is_numeric($_GET['c'])){
  	$idc = $this->padre->bd->seleccion_unica("idc from categorias where idc=".intval($_GET['c']));
  	if ($idc){
  	 $where .= " and categoria = ".$idc;
  	}
  	
   }
  }
 /* //--------------------------- TIPO ----------------------------------------------------
  if (isset($datos[0]) && is_string($datos[0]) && array_key_exists($datos[0],$this->padre->tipos)){
   $tipo = $datos[0];
   switch ($tipo){
   	case "hotel":
   	 $campos = ",fecha_alta, videos, municipio,superficie, habitaciones,precio";
   	break;
   	case "parcela": case "finca": case "propiedad":
   	 $campos = ",videos, municipio, precio "; //TIPO DE SUELO?
   	break;
   	case "trueque":
   	 $campos = ",videos_cambio, municipio, cambio_edificacion, cambio_terreno, cambio_cosa";
   	 break;
    default:
   	 $campos = "";
   	break;
   }
   if (!empty($campos)) {
   	$where .= " and ca.tipo='$tipo' ";
   	array_shift($datos);
   }
  } */
  //---------------------------- PROVINCIA ----------------------------------------------
  if (isset($_GET['p']) && is_string($_GET['p'])){
  	if ($_GET['p']!=''){
  	 $where .= " and lower(c.provincia)='".strtolower(zen_sanar($datos[0]))."'";
  	}
  }
  
  //---------------------------- HABITACIONES ------------------------------------------------
  if (isset($_GET['h']) && is_numeric($_GET['h'])){
   $where .= " and habitaciones>=".abs(intval($_GET['h']));
   array_shift($datos);
  }
  //---------------------------- PRECIO  ------------------------------------------------
  if (isset($_GET['pr']) && is_numeric($_GET['pr'])){ //Precio?
   $precio = abs(intval($_GET['pr']));
   $where .= " and precio>=$precio ";
   array_shift($datos);
  }
/*
  //--Tipo de transacción:
  if (isset($_GET['t']) && in_array($_GET['t'],$this->padre->secciones)){
   $where .= " and seccion='".addslashes($_GET['t'])."' ";
  }
  //--Propiedad (categoria):
  if (isset($_GET['pr']) && is_numeric($_GET['pr'])){
   $where .= " and categoria='".intval($_GET['pr'])."' ";
  }
  */
  //......CONSULTA.................
  $l = $this->padre->padre->idioma;
  $r = $this->padre->bd->seleccion(
  	"c.idc,url_amiga, titulo_$l, imagenes, lat, lng,ca.seccion, intro_$l,".
  	" url_amiga,provincia,habitaciones,precio,fecha_alta,tipo $campos FROM contenidos c INNER JOIN categorias ca ON categoria = ca.idc $where");
  echo '[';
  $b = false;
  while ($r && $f = $this->padre->bd->obtener_fila($r)) {

    $img = split(",",$f['imagenes']); //ALFFFFFFFFFFFFF   
   if ($b) echo ",\n";
   echo '{"marker": { "descripcion": "'.zen_borraEspacios(substr($f['intro_'.$l],0,150)).'..."';
   for ($i=0; $i<4; $i++){
      echo ',"imagen'.$i.'": '; //ALFFFFFFFFFFFFF 
      echo (empty($img[$i])?'null':'"'.$img[$i].'"');  //ALFFFFFFFFFFFFF
   }
   echo ',"titulo": "'.
    $f['titulo_'.$l].'","id":'.$f['idc'].', "lng": '.$f['lng'].', "precio": '.$f['precio'].', "tipo": "'.
    $f['tipo'].'", "lat": '.$f['lat'].', "seccion": "'.$f['seccion'].'","url_amiga": "'.$f['url_amiga'].'","provincia":'.
    '"'.$f['provincia'].'","fecha_alta":"'.zen_parsear_fecha_a_normal($f['fecha_alta']).'","habitaciones":'.$f['habitaciones'].
    '}}';
   if (!$b) $b = true;
  }
  echo ']';
  if (isset($_GET['debug']))
   $this->padre->bd->mostrar_ultima_consulta();
 }
 /**
  * Muestra sólo datos de una sección y un tipo
  *
  * @param array $datos
  * @param str $seccion
  * @param str $tipo
  * @return str:html
  */
 function mostrar(&$datos,$seccion="", $tipo=""){
  if (!isset($datos[0]) || !isset($datos[1])) { header("Location: /?falta-mostrar"); die(); }
  $where = empty($seccion)?"":"and seccion='".$seccion."' ";
  $where.= empty($tipo)?"":"and tipo='".$tipo."' ";
  $l  = $this->padre->padre->idioma;
  $d  = $this->padre->bd->obtener_fila_unica(
   $this->padre->bd->seleccion(
  	"c.idc,categoria,referencia,tlf_contacto,nombre_contacto as usuario,descripcion_$l,url_amiga,nombre_contacto,direccion,provincia,ciudad,DATE_FORMAT(fecha,'%d/%m/%y') as fecha,imagenes,titulo_$l,ca.nombre_$l as nombre_categoria,".
  	"ciudades_proximas,distancia_pueblo_cercano,ccaa,videos,cp,tipo_inmueble,categoria_equiv,if (url_confidencial=0,url,'') as url,estado_conservacion,habitaciones,superficie,superficie_util,".
  	"numero_plazas,num_habitaciones_ampliar,facturacion_anual,".
  	"porcentaje_ocupacion,nuevas_instalaciones_posibles,".
  	"if(licencia_actividad=1,'".TXT_SI."','".TXT_NO."') as licencia_actividad,".
  	"if(licencia_apertura=1,'".TXT_SI."','".TXT_NO."') as licencia_apertura,tecnologia,".
  	"if(licencia_obra=1,'".TXT_SI."','".TXT_NO."') as licencia_obra,".
  	"otras_licencias,fauna,flora,recursos_turisticos,comunicaciones,accesos,".
  	"if(vistas=1,'".TXT_SI."','".TXT_NO."') as vistas,".
  	"if(spa=1,'".TXT_SI."','".TXT_NO."') as spa,".
  	"if(restaurante=1,'".TXT_SI."','".TXT_NO."') as restaurante,".
  	"if(piscina=1,'".TXT_SI."','".TXT_NO."') as piscina,".
  	"if(sala_banquetes=1,'".TXT_SI."','".TXT_NO."') as sala_banquetes,".
  	"if(cafeteria=1,'".TXT_SI."','".TXT_NO."') as cafeteria,".
  	"if(bar=1,'".TXT_SI."','".TXT_NO."') as bar,".
  	"if(capilla=1,'".TXT_SI."','".TXT_NO."') as capilla,".
  	"if(jardines=1,'".TXT_SI."','".TXT_NO."') as jardines,".
  	"if(jardines=1,'".TXT_SI."','".TXT_NO."') as jardines,".
  	"if(ascensores=1,'".TXT_SI."','".TXT_NO."') as ascensores,".
  	"if(aceptaria_alquiler=1,'".TXT_SI."','".TXT_NO."') as aceptaria_alquiler,".
  	"if(aceptaria_otras=1,'".TXT_SI."','".TXT_NO."') as aceptaria_otras,".
  	"if(aceptaria_alquiler_compra=1,'".TXT_SI."','".TXT_NO."') as aceptaria_alquiler_compra,".
  	"if(aceptaria_permuta=1,'".TXT_SI."','".TXT_NO."') as aceptaria_permuta,".
  	"if(sala_reuniones=1,'".TXT_SI."','".TXT_NO."') as sala_reuniones,".
  	"otros_servicios_$l as otros_servicios,decoracion,historia_hotel_$l as historia_hotel,anio_construccion,anio_ultima_reforma,".
  	"motivo_venta,notas_$l as notas,".
  	" imagenes, lat, lng,ca.seccion, intro_$l, url_amiga,precio, tipo FROM contenidos c".
  	" INNER JOIN categorias ca ON categoria = ca.idc where publicado=1 and c.idc=".intval($datos[0])." and url_amiga='".
  	$datos[1]."' $where"
   )
  );
  
  if (!$d) { 
  	die("No existe el contenido");
  } else {
  	$this->c['tipomarcas'] = "markers/u/$seccion/{$d['categoria']}/";
  	$imagenes = split(",",$d['imagenes']);
  	$d['imagen'] = $imagenes[0];
  	$this->c['titulo'] = $d['titulo_'.$l];
  	//Cargar script HighSlide JS
  	$this->c['scripts'] .= '<script type="text/javascript" src="/media/js/highslide/highslide-with-gallery.js"></script>';
  	$this->c['css'] .=     '<link rel="stylesheet" type="text/css" href="/media/js/highslide/highslide.css" />';
	$this->c['scripts'] .= '<script type="text/javascript" defer="defer">
	 function mi_hs(){
		hs.graphicsDir = \'/media/js/highslide/graphics/\';
		hs.align = \'center\';
		hs.transitions = [\'expand\', \'crossfade\'];
		hs.outlineType = \'rounded-white\';
		hs.fadeInOut = true;
		hs.numberPosition = \'caption\';
		hs.dimmingOpacity = 0.75;
	
		// Add the controlbar
		if (hs.addSlideshow) hs.addSlideshow({
			//slideshowGroup: \'group1\',
			interval: 5000,
			repeat: false,
			useControls: true,
			fixedControls: \'fit\',
			overlayOptions: {
			opacity: .75,
			position: \'bottom center\',
			hideOnMouseOut: true
		}
		});
	 }
	</script>';
	$this->c['onloads'] =  str_replace(";",'; mi_hs();',$this->c['onloads']);
	$d['imagenes'] = "";
	$n = count($imagenes);
	for ($i=1; $i<$n; $i++){
	 if (!empty($imagenes[$i])){
	  $d['imagenes'] .= $this->p->rellena_HTML(
	   "mapas/imgs_gal.html",array(
	    "i"=>$i+1,
	    "seccion"    => $d['seccion'],
	    "imagen"     => $imagenes[$i],
	    "titulo_".$l => $d['titulo_'.$l]
	   )
	  );
	 }
	}
	
  	return $this->p->contenido_reemplaza("mapas/mostrar.html",$d);
  }
 }
 /**
  * Función en desuso
  */
 function listar(){
  $html = "var json=[]; ";
  $center_lat = 40;
  $center_lng = -100;
  $radius = 25;
/*$query = sprintf("SELECT address, name, lat, lng, ( 3959 * acos( cos( radians('%s') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( lat ) ) ) ) AS distance FROM markers HAVING distance < '%s' ORDER BY distance LIMIT 0 , 20",
  mysql_real_escape_string($center_lat),
  mysql_real_escape_string($center_lng),
  mysql_real_escape_string($center_lat),
  mysql_real_escape_string($radius));*/
  $r = $this->padre->bd->seleccion("* from markers");
  if (!$r) {
   return ("Invalid query: " . mysql_error());
  } 
  $i = 0;
  while ($row = $this->padre->bd->obtener_fila($r)){ 
   $html .= "json[".$i."]={'id':".$i.", 'lat':".$row['lat'].", 'lng':".$row['lng']." };
   ";
  /*$newnode->setAttribute("name", $row['name']);
  $newnode->setAttribute("address", $row['address']);
  $newnode->setAttribute("lat", $row['lat']);
  $newnode->setAttribute("lng", $row['lng']);
  $newnode->setAttribute("distance", $row['distance']);
  */
  $i++;
  }
  echo $html;
 }
 /**
 * Presenta todas las imagenes de la galeria con un programa en JavaScript para ir alternando entre ellas...
 * @param str $directorio (ej.: img/presentacion)
 * @param str $listado
 * @param str $seccion
 * @param int $max_thumbs //Maximo numero de thumbnails
 * @return str (html)
 */
 function galeria($directorio="",$listado="",$seccion="",$max_thumbs = 5){
  $p = new zen_plantilla();
  $i = 0; //contador de imagenes de thumbnails
  $fotos = $imagen_principal = $imagenes_thumbnails = "";
  
  if (!empty($directorio)){
  	$p->cargar('galeria/indice.html');
	if (!is_dir(ZF_DIR_MEDIA.$directorio)) return "Directorio no v&aacute;lido";
	$dir = opendir(ZF_DIR_MEDIA.$directorio);
	while ($entrada = readdir($dir)){
		if ($entrada =="." || $entrada == ".." || !is_file(ZF_DIR_MEDIA.$directorio.'/'.$entrada)) continue;
		if (!empty($fotos))$fotos .= ","; //Para evitar error en JS para IExplorer
		if (empty($imagen_principal)) $imagen_principal = "media/".$directorio."/".$entrada;
		$fotos .= '"'.$entrada.'"';
		if ($i<$max_thumbs){
			$imagenes_thumbnails .= '<a id="enlace_thumb_'.$i.'" href="javascript:ver_foto('.$i.
			');"><img id="thumb_'.$i.'" src="media/'.$directorio.'/thumbs/'.$entrada.
			'" height="69" width="104" border="0"></a>'."\n";
			$i++;
		}
	}
  } else {
  	$p->cargar('galeria/indice.html');
  	$listado = zen_deserializar($listado);
  	$n = count($listado);
  	if (!$n) return "";
  	for ($j=0; $j<$n; $j++){
  	 $entrada = $listado[$j];
  	 if (!empty($fotos))$fotos .= ","; //Para evitar error en JS para IExplorer
		if (empty($imagen_principal)) $imagen_principal = "media/img/".$seccion."/".$entrada;
		$fotos .= '"'.$entrada.'"';
		if ($i<$max_thumbs){
			$imagenes_thumbnails .= '<a id="enlace_thumb_'.$i.'" href="javascript:ver_foto('.$i.
			');"><img id="thumb_'.$i.'" src="media/img/'.$seccion.'/thumbs/'.$entrada.
			'" height="69" width="104" border="0"></a>'."\n";
			$i++;
		}
  	}
  	$directorio = "img/".$seccion; //para que guarde correlacion
  }
  $p->pasarAplantilla(array(
	'imagen_principal'=>$imagen_principal,
	'directorio' => "media/".$directorio."/",
	'ARRAY_FOTOS' => '[ '.$fotos.' ]',
	'IMAGENES_THUMBNAILS'=>$imagenes_thumbnails,
	'MAX_THUMBS'=>$max_thumbs)
	);
  return $p->contenido;
 }
 /**
  * Listado de documentos con <li>
  *
  * @param str $listado
  * @param str $fichero_plantilla
  * @return str
  */
 function documentos($listado,$fichero_plantilla){
  $listado = zen_deserializar($listado);
  
  if (!is_array($listado)) return "";
  $n = count($listado);
  $p = new zen_plantilla();
  $html = "";
  for ($i=0; $i<$n; $i++){
   $nom = $listado[$i];
   /*$siz = filesize(ZF_DIR_MEDIA.'documentos'.DIRECTORY_SEPARATOR.$nom); 
   $siz = $siz<1024?$siz." B":floor($siz/1024/1024)."KB";*/
   $_nom= split("_",$nom); array_shift($_nom);
   $nom = implode(" ",$_nom);
   $nomb= ucfirst(substr($nom,3,strlen($nom)));
   $html .= $p->rellena_HTML($fichero_plantilla,array(
    "nombre_documento" => $nom,
    "documento" => $listado[$i]//." ($siz)"
   ));
  }
  return $html;
 }
 
 /**
  * Devuelve una lista en HTML de imagenes asociadas a un articulo
  * @param str $imagenes
  * @param str $seccion para saber el subdirectorio de imgs
  * @return str 
  */
 function cargar_imagenes(&$imagenes,$seccion){
  $html = "";
  $ims = zen_deserializar($imagenes); 
  $n = count($ims);
  for ($i=0; $i<$n; $i++){
	 $im = ZF_SITIO_WEB.'media/img/'.$seccion.'/'.$ims[$i];
	 $html .= '<a href="'.$im.'" target="_blank"><img width="55" src="'.$im.
	 	'" border="0"></a>[<label for="borrar_'.$ims[$i].
	 	'">borrar</label><input type="checkbox" id="borrar_'.$ims[$i]
	 	.'" name="borrar_'.$ims[$i].'"/>]<br/>';
  }
  return $html;
 }
 /**
 function listado_articulos($campos,$categoria,$seccion,$id_seleccionado,&$p,$orden="order by idc desc"){
 	$articulos = new zen_modelo_datos(
 	 $this->padre->padre,$campos,"contenidos","","where seccion='$seccion' and categoria=$categoria $orden");
 	$todos = $articulos->obtener();
 	$n = count($todos);
 	
 	$html  = "";
 	$idi   = $this->padre->padre->idioma;
 	for ($i=0; $i<$n; $i++){
 	 $todos[$i]['activa'] = ($id_seleccionado==$todos[$i]['idc'])?'submenu_seleccionada':'';
 	 if (!empty($todos[$i]['documentos_'.$idi])) 
 	  $todos[$i]['documentos'] = $this->documentos($todos[$i]['documentos_'.$idi],"contenidos/$seccion/documentos.html");
 	 else 
 	  $todos[$i]['documentos'] = "";
 	 if (!empty($todos[$i]['fecha'])) $todos[$i]['fecha'] = zen_parsear_fecha_a_normal($todos[$i]['fecha']);
 	 $html .= $p->contenido_reemplaza("contenidos/$seccion/articulos.html",$todos[$i]);
 	}
 	return $html;
 }*/
 
 
}
?>