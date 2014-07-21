<?php
class ruralvia extends zen_aplicacion {
 /**
  * Visualizador
  *
  * @var html_ruralvia
  */
 var $html;
 /**
  * @var contenidos
  *
 var $contenidos;*/
 /**
  * @var markers
  */
 var $markers;
 /**
  * @var servicios
  */
 var $servicios;
 /**
  * Constructor
  *
  * @return ruralvia
  */
 function ruralvia(){
  parent::zen_aplicacion();
  //Idiomas
  zen___carga_constantes_idiomas("general",$this->idioma);
  zen___importar_modelos($this,"markers,servicios");
  $this->html = new html_ruralvia($this);
  
 }
 /**
  * Hacemos una interseccion de los dos grupos y si da algún valor, es que tiene permisos
  *
  * @param array $grupos_permitidos
  * @param array||null $mis_grupos
  * @return int
  */
 function tengo_permiso($grupos_permitidos,$mis_grupos=null){
 	if (is_null($mis_grupos)){
 	 if (!isset($_SESSION['grupos'])||!is_array($_SESSION['grupos'])){
 	 	$mis_grupos = array();
 	 } else {
 	 	$mis_grupos = $_SESSION['grupos'];
 	 }
 	}
  	return count(array_intersect($grupos_permitidos,$mis_grupos));
 }
}
class html_ruralvia extends zen_html {
 /**
  * ruralvia Web app.
  *
  * @var ruralvia
  */
 var $padre;
 /**
  * Puntero al contenido
  *
  * @var array
  */
 var $c;
 /**
  * Constructor
  *
  * @param ruralvia $padre
  * @return html_ruralvia
  */
 function html_ruralvia($padre){
  parent::zen_html($padre);
  $this->c = $this->padre->contenido;
  //Inicializaciones
  $this->c['meta_keys'] = TXT_META_KEYS;
  $this->c['meta_desc'] = TXT_META_DESC;
  $this->c['scripts'] = $this->c['css'] = $this->c['onloads'] =
    $this->c['ruta'] =  
    $this->c['tabla_resultados'] = $this->c['filtros'] = "";
  $this->c['tipomarcas'] = 'markers/u/destacados/';
  $this->c['info_login'] = isset($_SESSION['idu'])?$this->plantilla->rellena_HTML("login/info.html",array("login"=>$_SESSION['login'],"nombre"=>$_SESSION['nombre'])):$this->plantilla->devolver_contenido("login/links.html");
 }
 
 function scripts_mapa($tipo){
  switch ($tipo){
  	case "inicio": default:
  	 $this->c['scripts']   = $this->plantilla->rellena_HTML(
      "mapas/indice.html",array(
      "centerLatitud" => ruralvia_lat,
      "centerLongitud"=> ruralvia_lng,
      "gmaps_api_key" => ruralvia_gmaps_key,
      "masinfo" => TXT_MAS_INFO
     )
    );
    $this->c['onloads'] = 'onload="init();"';
    $this->c['tabla_resultados'] = $this->plantilla->devolver_contenido("mapas/tabla_r.html");
    $this->c['filtros'] = $this->plantilla->rellena_HTML("mapas/filtros.html",array(
     "transacciones" => $this->construir_secciones_select()
    ));
  	break;
   case "gratis":
   	$this->c['scripts'] = '<script type="text/javascript" src="/media/js/anadir_images.js"></script>'
  						 .'<script type="text/javascript" src="http://www.google.com/jsapi?key='.
  						 	ruralvia_gmaps_key.'"></script>
  						   <script type="text/javascript" src="/media/js/anadir_coord.js"></script>';
   	break;
  }
 }
 
 function servicio($datos){
  if (!isset($datos[0])) $this->padre->servicios->html->index();
  else $this->padre->servicios->html->ver($datos);
 }
 function mostrar_web($titulo,$menu_seleccionado=''){
  if (!empty($titulo)) $this->c['titulo'] = $titulo;
  $this->construir_menus($menu_seleccionado);
  parent::index();
 }
 
 function construir_menus($menu_seleccionado){
  foreach ($this->padre->markers->secciones as $seccion => $nombre_seccion){
   $r = $this->padre->bd->seleccion("idc, nombre_".$this->padre->idioma." from categorias where seccion='$seccion'");
   $html['elementos']  ="";
  // $this->padre->bd->mostrar_ultima_consulta();
   while ($r && $f = $this->padre->bd->obtener_fila($r)) {
    $html['elementos'] .= $this->plantilla->rellena_HTML("el_menus.html",array(
     "seccion" => $seccion,
     "nombre_".$this->padre->idioma => $f['nombre_'.$this->padre->idioma],
     "nombre_formateado" => zen_codifica_nombre_para_url($f['nombre_'.$this->padre->idioma]),
     "idc" => $f['idc']
    ));	
   }
   $html['seccion'] = $seccion;
   if (!empty($html['elementos']))
    $this->c['menus_'.$seccion] = $this->plantilla->contenido_reemplaza("menus_js.html",$html);
   else 
    $this->c['menus_'.$seccion] = "";
  }
 }
 
 /**
  * Se le pasa en $datos[0] el IDC y en $datos[1] el url_amiga
  *
  * @param array $datos
  * @param str $sec
  * @param str $tipo
  */
 function ver(&$datos,$sec="",$tipo=""){
  $this->scripts_mapa("inicio");
  if (isset($datos[0]) && !is_numeric($datos[0])){
   array_shift($datos);
   if (!isset($datos[0])||!isset($datos[1])) header("Location: /");//?datos[0]-datos[1]
   $datos[1] = str_replace("_","-",$datos[1]); //recuperar los "-" reemplazados por el enrutador para url_amigas 
   $this->c['contenido'] = $this->padre->markers->html->mostrar($datos, $sec, $tipo);
  } else {
  	//Es una categoria
  	$this->c['contenido'] = $this->plantilla->devolver_contenido("indice.html");
  	$this->c['tipomarcas']= "markers/u/$sec/{$datos[0]}/";
  	$this->c['titulo'] = $this->padre->markers->secciones[$sec]." | ".
  	 $this->padre->bd->seleccion_unica(
  	  "nombre_".$this->padre->idioma." from categorias where idc=".intval($datos[0])
  	 );
  }
  
  $this->mostrar_web("");
 }
 
 function procesa_url(&$datos, $seccion){
  array_unshift($datos,$seccion);
  if (!isset($datos[1])) { header("Location: /?tipo"); die(); }
  array_shift($datos); //quitamos la seccion y el tipo, nos quedamos con lo que queda, la url_amiga
  if (!isset($datos[0]) || !array_key_exists($datos[0],$this->padre->markers->tipos)) { header("Location: /?tipo"); die(); }
  $tipo = $datos[0];
  array_shift($datos);
  
  //Solo queda el IDC y la url_amiga
  $this->ver($datos,$seccion,$tipo);
 }
 
 function genera_mapa($tipo){
  $this->c['contenido'] = $this->plantilla->devolver_contenido("indice.html");
  $this->scripts_mapa("inicio");
  $this->c['tipomarcas'] = "markers/u/$tipo/";
  $this->mostrar_web($this->padre->markers->secciones[$tipo]);
 }
 function alquiler(&$datos){
  $this->ver($datos,"alquiler");
 }
 function compra(&$datos){
  $this->ver($datos, "compra");
 }
 function trueque(&$datos){
  $this->ver($datos,"trueque");
 }
 function traspaso(&$datos){
  $this->ver($datos, "traspaso");
 }
 function gratis(&$datos){
  $this->ver($datos, "gratis");
 }
 function autopromo(&$datos){
  $this->ver($datos, "autopromo");
 }
 function venta(&$datos){
  $this->ver($datos, "venta");
 }
 function se_alquila(&$datos){
  $this->genera_mapa($datos,"alquiler");
 }
 function se_trueca(&$datos){
  $this->genera_mapa($datos,"trueque");	
 }
 function se_traspasa(&$datos){
  $this->genera_mapa($datos,"traspaso");
 }
 function promo_gratis(&$datos){
  $this->genera_mapa($datos,"gratis");
 }
 function concursar(&$datos){
  $this->genera_mapa($datos,"autopromo");
 }
 function se_vende(){
  $this->genera_mapa("venta");
 }
 function se_compra(&$datos){
  $this->genera_mapa($datos,"compra");	
 }
 
 /*
 function se_compra(){
  $this->c['contenido'] = $this->plantilla->devolver_contenido("indice.html");
  $this->scripts_mapa("inicio");
  $this->c['tipomarcas'] = "markers/u/venta/";
  $this->mostrar_web(TITULO_SE_COMPRA);
 }*/
 function construir_secciones_select($seleccionada=""){
  $html = "";
  foreach ($this->padre->markers->secciones as $i => $sec){
   $html .= '<option value="'.$i;
   $html .= $seleccionada==$i?' selected ':'';
   $html .= '">'.$sec."</option>\n";
  }
  return $html;
 }
 function construir_select($nombre,$tabla,$seleccionado=""){
  $html = "";
  foreach ($this->padre->bd->obtener_set($nombre,$tabla) as $k){   
    $html .= '<option value="'.$k.'"';
  	$html .= $seleccionado==$k?' selected ':'';
  	$html .= '>'.$k."</option>\n";
  }
  return $html;	
 }
 function formatear_campos($tipo,&$articulo){
  $checks = $ints = $dates = array();
  switch ($tipo){
  	case "hotel":
  	 $checks = array("licencia_actividad","titulo_confidencial","tlf_confidencial","url_confidencial","licencia_apertura",
  	 "restaurante","spa","piscina","cafeteria","bar","capilla","jardines","sala_banquetes","ascensores","decoracion",
  	 "aceptaria_alquiler","aceptaria_alquiler_compra","aceptaria_permuta","aceptaria_otras",
  	 "sala_reuniones","licencia_obra");
  	 break;
  	case "casa": case "alojamiento":
  	 $checks = array("tlf_confidencial");
  	 break;
  	case "parcela": case "finca": case "propiedad":
  	 $checks = array("licencia_actividad","licencia_apertura","servidumbres","restaurante","spa","piscina","cafeteria","bar",
  	 "capilla","jardines","sala_banquetes","ascensores","decoracion",
  	 "aceptaria_alquiler","aceptaria_alquiler_compra","aceptaria_permuta","aceptaria_otras",
  	 "sala_reuniones","licencia_obra");
  	 break;
  }
  foreach ($checks as $check) {
  	$articulo[$check] = $articulo[$check]==1?'checked':'';
  }
 }
 function construir_array_zonas_js(){
  $z= new zen_modelo_datos($this->padre,"","zonas_js");
  $le = $this->padre->idioma;
  $zonas= $z->obtener("nombre_$le,lat,lng,provincia");
  $n = count($zonas);
  $js = "var zonas_js = new Array($n);\n";
  for($i=0;$i<$n;$i++){
  	$js .= "zonas_js[$i] = new Array(4);\n";
  	//lat,lng,nombre,provincia
  	$js .= "zonas_js[$i][0] = ".$zonas[$i]['lat'].";\n";
  	$js .= "zonas_js[$i][1] = ".$zonas[$i]['lng'].";\n";
  	$js .= "zonas_js[$i][2] = '".$zonas[$i]['nombre_'.$le]."';\n";
  	$js .= "zonas_js[$i][3] = '".$zonas[$i]['provincia']."';\n";
  }
  return $js;
 } 
 function poner_anuncio_gratis($datos){
  $op = isset($datos[0])?zen_sanar($datos[0]):"";
  $le = $this->padre->idioma;
  switch ($op) {
  	//Solo entrar le mostramos para que elija:
   case "":
   default:
  	 $this->c['titulo']    = TITULO_PONER_ANUNCIO;
  	 $this->c['contenido'] = $this->plantilla->rellena_HTML("gratis/indice.html",array(
  	  "secciones" => $this->construir_secciones_select(),
  	  "lat" => ruralvia_lat,
  	  "lng" => ruralvia_lng,
  	  "nombre" => isset($_SESSION['nombre'])?$_SESSION['nombre']:"",
  	  "email"  => isset($_SESSION['email'])?$_SESSION['email']:"",
  	  'tipos_propietario'  => $this->construir_select("tipo_propietario","contenidos",null),
  	  "info_login" => isset($_SESSION['idu'])?
  	  		$this->plantilla->rellena_HTML("gratis/info_login.html",$_SESSION):
  	  		$this->plantilla->rellena_HTML("login/links.html",$_SESSION),
  	  "zonas_js" => $this->construir_array_zonas_js()
  	 ));
  	 $this->scripts_mapa("gratis");
  	 break;
   case "seccion": //listado de categorias de una seccion
  	 if (isset($datos[1]) && array_key_exists($datos[1],$this->padre->markers->secciones)){
  	  $r = $this->padre->bd->seleccion("idc,nombre_$le from categorias where seccion='".zen_sanar($datos[1])."'");
  	  echo "[";
  	  $n = $this->padre->bd->num_filas_resultantes();
  	  $i = 0;
  	  while ($r && $c = $this->padre->bd->obtener_fila($r)) {
  	  	echo '{"idc": '.$c['idc'].',"nombre":"'.$c['nombre_'.$le].'"}';
  	  	$i++;
  	  	if ($i<$n) echo ","; //separacion entre elementos del array
  	  }
  	  echo "]";
  	 }
  	 return ; //no hay nada mas que mostrar
  	break;
   case "form": //Mostrar un formulario para una seccion
    if (isset($datos[1]) && array_key_exists($datos[1],$this->padre->markers->secciones)){
     $ids = zen_sanar($datos[1]);
     $idc = isset($datos[2])&&is_numeric($datos[2])?$this->padre->bd->seleccion_unica(
      "idc from categorias where seccion='$ids' and idc=".intval($datos[2])
      ):null;
     if (isset($datos[2])){ //Existe la categoria y la seccion seleccionadas, ahora a por el formulario!
      //Tipo de formulario a crear:
      $tipo = $this->padre->bd->seleccion_unica("tipo from categorias where idc=".intval($idc));
      $nomc = $this->padre->bd->seleccion_unica("nombre_$le from categorias where idc=".intval($idc));
      echo $this->plantilla->rellena_HTML("gratis/secciones/crear_$tipo.html",array(
       "nombre_seccion"   => $this->padre->markers->secciones[$ids],
       "nombre_categoria" => $nomc,
       'seccion' 	 		=> $ids,
       'categoria'          => $idc,
   	   'fecha'       		=> date("d/m/Y"),
       'fecha_alta'         => date("d/m/Y"),
       'tipos_inmueble' 	=> $this->construir_select("tipo_inmueble","contenidos",null),
       'tipos_terreno'      => $this->construir_select("tipo_terreno","contenidos",null),
       'ccaas'				=> $this->construir_select("ccaa","contenidos",null),
       '_caracteristicas_fisicas' => $this->construir_select("caracteristicas_fisicas","contenidos",null),
       'decoraciones'      => $this->construir_select("decoracion","contenidos",null),
       'orientaciones'      => $this->construir_select("orientacion","contenidos",null),
       'imagenes' 			=> "", 
       'documentos'         => "",
      ));
     }
    }
    return ; //no hay nada mas que mostrar
   	break;
   case "crear": 
      list($sec,$cat,$tipo)= $this->padre->markers->comprobar_seccion_categoria($datos);
  	  if (!$sec) die (sprintf("No hay seccion %d",$sec)); //No existe...o alguna trampa se ha intentado
	  $this->c['titulo']  = "Creando nuevo [{$this->padre->markers->secciones[$sec]} ] - $tipo ";
	  if (!isset($_POST['titulo_'.$le])) {
	  	$co = "Rellene todos los campos. <a href='javascript:window.history.back();'>Volver</a>";
	  } else {
	  	$this->c['titulo'].= "- ({$_POST['titulo_'.$le]}) - ";
	  	$_REQUEST['fecha'] = zen_parsear_fecha_a_mysql($_REQUEST['fecha']);
	  	$_REQUEST['fecha_alta'] = isset($_REQUEST['fecha_alta'])?zen_parsear_fecha_a_mysql($_REQUEST['fecha_alta']):date("Y-m-d");
	  	$_REQUEST['modificado'] = date("Y-m-d H:i:s");//2009-06-16 10:49:19		// ALFONSO		//$_REQUEST['url_amiga']  = zen_codifica_nombre_para_url($_REQUEST['titulo_es']);
	  	$_REQUEST['url_amiga']  = zen_codifica_nombre_para_url(str_replace('"','',$_REQUEST['titulo_es']));
	  	$_REQUEST['imagenes']   = $this->guardar_imagenes(null,$sec);
	  	$_REQUEST['publicado']  = false;
	  	$_REQUEST['categoria']  = $cat;
	  	//$_REQUEST['documentos'] = $this->guardar_documentos(null);
	  	zen___carga_funciones("zen_ficheros"); 
	  	$errores = "";
	  	$miniatura = zen_guardarFichero("miniatura",ZF_DIR_MEDIA.'img'.DIRECTORY_SEPARATOR.$sec.DIRECTORY_SEPARATOR,$errores);
	  	if (!empty($miniatura) && empty($errores)) $_REQUEST['miniatura'] =& $miniatura;
	  	$_REQUEST['idu'] = isset($_SESSION['idu'])?$_SESSION['idu']:'';
	  	if (!empty($_REQUEST['imagenes'])){
	  	 $idc = !$this->padre->bd->seleccion_unica("titulo_es from contenidos where lower(titulo_es)='".
	  		zen_sanar($_REQUEST['titulo_es'])."'")?$this->padre->markers->insertar($_REQUEST):false;
	  	} else {
	  	 $idc = false;
	  	}
	  	if ($idc){
	  	 $co = '<img src="/media/images/icon_ok.gif" alt="Correcto"/> '.TXT_ANUNCIO_OK;
	  	 zen_enviarCorreo(ZF_CORREO_ADMIN,"Anuncio: ".$this->c['titulo'],"Provincia:".$_REQUEST['provincia'].
	  	 	'<br>Intro:'.$_REQUEST['intro_es'].'<br>Descripcion:'.$_REQUEST['descripcion_es'].
	  	 	"<br>Para publicarlo vaya aqui: <a href='".
	  	 	//http://www.inmorusticas.com/admin/index.php/contenidos/articulos/traspaso/21/listar/
	  	 	"http://www.inmorusticas.com/admin/index.php/contenidos/articulos/$sec/$cat/listar/'>".
	  	 	"http://www.inmorusticas.com/admin/index.php/contenidos/articulos/$sec/$cat/listar/</a>".
	  	 	"<br> Y para editarlo aqui:<br>".
	  	 	//http://www.inmorusticas.com/admin/index.php/contenidos/articulos/traspaso/21/editar/27/
	  	 	'<a href="'.
	  	 	"http://www.inmorusticas.com/admin/index.php/contenidos/articulos/$sec/$cat/editar/$idc/".
	  	 	'">'.
	  	 	"http://www.inmorusticas.com/admin/index.php/contenidos/articulos/$sec/$cat/editar/$idc/".
	  	 	'</a>'
	  	 	);
	  	  //Crear el usuario 
	  	  $correo = strtolower(zen_sanar($_REQUEST['e_mail_contacto']));
	  	  if (!empty($correo))
	  	  if (!$this->padre->bd->seleccion_unica("idu from usuarios where lower(email)='".$correo."'")){
	  	   $pass= substr(md5(substr($correo,1,6)),3,6);
	  	   $idu = $this->padre->bd->insertar(
	  	    "usuarios(idu,nombre,contacto,web,direccion,login,rol,email,password) values(0,'".
	  	    (zen_sanar($_REQUEST['nombre_contacto']))."','".(zen_sanar($_REQUEST['nombre_contacto']))."','".
	  	    (zen_sanar($_REQUEST['url']))."','".(zen_sanar($_REQUEST['direccion']))."','$correo','usuario','$correo',md5('$pass') );"
	  	   );
	  	   if ($idu){
	  	   	zen___carga_constantes_idiomas("registro",$le);
	  	    $codigo = substr(md5($correo),3,6).substr(md5(zen_sanar($_REQUEST['nombre_contacto'])),3,6);
		    $url = ZF_SITIO_WEB."registro/confirmar/$idu/$codigo/";
  		    if (zen_enviarCorreo($correo,TXT_CUENTA_CREADA,
   		     sprintf(TXT_MAIL_CUENTA_CREADA.'<br/>Password: '.$pass,$url,$url),ZF_CORREO_ADMIN)
   		    ){
   		     //Poner el idu a su anuncio
   		     @$this->padre->bd->actualizar("contenidos set idu=$idu where idc=$idc");
  		    } else {
  		     $error = TXT_ERROR_ENVIANDO_CORREO_ALTA;
  		    }
	  	   }
	  	  } else {
	  	  	//Enviar correo al usuario ya creado con su nuevo anuncio?
	  	  }
	  	} else {
	  	 $co = '<img src="/media/images/icon_error.gif" alt="Error creando"/>'.TXT_ERROR_CREANDO_ANUNCIO.'...<a href="javascript:window.history.back();';
	  	}
	  }
	  $this->c['contenido'] = $this->plantilla->rellena_HTML(
	  	"gratis/mensaje.html",array("mensaje"=>$co));
   	break;
   case "editar":
   	$this->c['titulo']    = TITULO_PONER_ANUNCIO;
   	$idc = intval($datos[1]);
   	if (!isset($_SESSION['idu'])) { header("Location: login.php"); die(); }
   	$editar = $this->padre->bd->obtener_fila_unica(
   	 $this->padre->bd->seleccion(
   	 "* from contenidos co INNER JOIN categorias ca ON co.categoria = ca.idc where co.idc=".
   	 $idc." and co.idu=".intval($_SESSION['idu']) //." and publicado=1"
   	));
	if (!$editar) die("No existe el contenido");
	$tipo = $this->padre->bd->seleccion_unica("tipo from categorias where idc=".$editar['categoria']);
	
	$nomc = $editar["nombre_$le"]; //$this->padre->bd->seleccion_unica("nombre_$le from categorias where idc=".$editar['categoria']);
    $ids  = $editar["seccion"]; // $this->padre->bd->seleccion_unica("seccion from categorias where idc=".$editar['categoria']);
  	
  	$editar['tipos_propietario']  = $this->construir_select("tipo_propietario","contenidos",$editar['tipo_propietario']);
  	$editar["nombre_seccion"]  = $this->padre->markers->secciones[$ids];
    $editar["nombre_categoria"] = $nomc;
    $editar['seccion'] 	 		= $ids;
    $editar['idc']              = $idc;
   	$editar['fecha']       		= zen_parsear_fecha_a_normal($editar['fecha']);
    $editar['tipos_inmueble'] 	= $this->construir_select("tipo_inmueble","contenidos",$editar['tipo_inmueble']);
    $editar['tipos_terreno']    = $this->construir_select("tipo_terreno","contenidos",$editar['tipo_terreno']);
    $editar['ccaas']			= $this->construir_select("ccaa","contenidos",$editar['ccaa']);
    $editar['_caracteristicas_fisicas'] = $this->construir_select("caracteristicas_fisicas","contenidos",$editar['caracteristicas_fisicas']);
    $editar['decoraciones']     = $this->construir_select("decoracion","contenidos",$editar['decoracion']);
    $editar['orientaciones']    = $this->construir_select("orientacion","contenidos",$editar['orientacion']);
    $editar['imagenes'] 		= $this->padre->markers->html->cargar_imagenes($editar['imagenes'],$ids);
    //checkbox:
    $chks = array('url_confidencial','licencia_actividad','licencia_apertura','licencia_obra','vistas',
    "restaurante","spa","piscina","cafeteria","bar","capilla","jardines","sala_banquetes","sala_reuniones",
    "ascensores","aceptaria_alquiler","aceptaria_alquiler_compra","aceptaria_permuta","aceptaria_otras",
    "titulo_confidencial");
    foreach ($chks as $chk){
     $editar[$chk] = $editar[$chk]?"checked":"";
    }
    
    $this->c['onloads'] = 'onload="IniciarMapa('.$editar['lng'].','.$editar['lat'].',true);" ';
  	$this->c['contenido'] = $this->plantilla->contenido_reemplaza("gratis/secciones/editar_$tipo.html",$editar);
  	$this->scripts_mapa("gratis");
   	break;
   case "actualizar":
   	//Comprobar que realmente se puede actualizar:
   	if (!isset($_SESSION['idu'])) { header("Location: login.php"); die(); }
   	$idc = intval($datos[1]);
   	$editar = $this->padre->bd->obtener_fila_unica(
   	 $this->padre->bd->seleccion(
   	 "* from contenidos co INNER JOIN categorias ca ON co.categoria = ca.idc where co.idc=".
   	 $idc." and co.idu=".intval($_SESSION['idu'])//." and publicado=1"
   	));
   	if (!$idc) die("No existe el contenido");
   	//Datos que no se pueden cambiar:
   	$unset = array("seccion","categoria","fecha_alta","nombre_contacto","tlf_contacto","nombre_contacto");
   	foreach ($unset as $i){
   	 unset($_REQUEST[$i]);
   	}
   	//Formateo de datos básicos:
  	$_REQUEST['fecha'] = zen_parsear_fecha_a_mysql($_REQUEST['fecha']);
  	
  	$_REQUEST['modificado'] = date("Y-m-d H:i:s");//2009-06-16 10:49:19
	$_REQUEST['url_amiga']  = zen_codifica_nombre_para_url($_REQUEST['titulo_es']);
	$_REQUEST['imagenes']   = $this->guardar_imagenes($idc,$editar['seccion']);
	$_REQUEST['publicado']  = false;
   	$_REQUEST['idu'] = $_SESSION['idu'];
   	$_REQUEST['idc'] = $idc;
   	if ($this->padre->markers->actualizar($_REQUEST)){
   	 $co['mensaje'] = '<img src="/media/images/icon_ok.gif" alt="Correcto"/> '.TXT_ANUNCIO_OK;
   	 zen_enviarCorreo(ZF_CORREO_ADMIN,"Anuncio actualizado: ".$_REQUEST['titulo_es'],"Provincia:".$_REQUEST['provincia'].
	  	 	'<br>Intro:'.$_REQUEST['intro_es'].'<br>Descripci&oacute;n:'.$_REQUEST['descripcion_es'].
	  	 	"<br>Para publicarlo vaya aqui: <a href='".
	  	 	//http://www.inmorusticas.com/admin/index.php/contenidos/articulos/traspaso/21/listar/
	  	 	"http://www.inmorusticas.com/admin/index.php/contenidos/articulos/{$editar['seccion']}/{$editar['categoria']}/listar/'>".
	  	 	"http://www.inmorusticas.com/admin/index.php/contenidos/articulos/{$editar['seccion']}/{$editar['categoria']}/listar/</a>".
	  	 	"<br> Y para editarlo aqui:<br>".
	  	 	//http://www.inmorusticas.com/admin/index.php/contenidos/articulos/traspaso/21/editar/27/
	  	 	'<a href="'.
	  	 	"http://www.inmorusticas.com/admin/index.php/contenidos/articulos/{$editar['seccion']}/{$editar['categoria']}/editar/$idc/".
	  	 	'">'.
	  	 	"http://www.inmorusticas.com/admin/index.php/contenidos/articulos/{$editar['seccion']}/{$editar['categoria']}/editar/$idc/".
	  	 	'</a>'
	  	 	);
   	} else {
   	 $co['mensaje'] = '<img src="/media/images/icon_error.gif" alt="Error creando"/>'.
   	 	TXT_ERROR_CREANDO_ANUNCIO.'...<a href="javascript:window.history.back();';
   	}
   	$this->c['contenido'] = $this->plantilla->contenido_reemplaza("gratis/mensaje.html",$co);
   	$this->c['titulo'] = TITULO_PONER_ANUNCIO;
   	break;
  }
  $this->mostrar_web("","");
 }
 /**
  * Guarda un array de imagenes y devuelve la lista de ellos que se ha logrado almacenar
  * tambien comprueba si se ha borrado alguno previamente
  * @param int $idc
  * @param str $seccion
  * @return str
  */
 function guardar_imagenes($idc=null,$seccion){  
  if (!is_null($idc))
   $ims = zen_deserializar($this->padre->bd->seleccion_unica("imagenes from contenidos where idc=".$idc)); 
  else 
   $ims = array();
  //Guardar imagenes:
  $errores = "";
  zen___carga_funciones("zen_ficheros");
  $guardar_imgs= zen_guardarFicheros("imagenes",ZF_DIR_MEDIA."img/$seccion/",$errores,rand(10,100)."_",true,104);
  $n = count($ims); 
  for ($i=0; $i<$n; $i++){ 
  	if (!isset($_POST['borrar_'.str_replace(".","_",$ims[$i])])) {
  	 array_push($guardar_imgs,$ims[$i]); 
  	} //else echo $_POST['borrar_'.$ims[$i]];
  }
  return zen_serializar($guardar_imgs);
 }
 function index(){
  $this->c['contenido'] = $this->plantilla->devolver_contenido("indice.html");
  $this->scripts_mapa("inicio");
  $this->mostrar_web(TITULO_HOME);
 }
 function aviso_legal(){
  $this->c['contenido'] = $this->plantilla->devolver_contenido("contenidos/aviso_legal.html");
  $this->c['ruta'] = $this->plantilla->rellena_HTML("contenidos/ruta.html",
  array("ruta"=>TITULO_AVISO_LEGAL." ","seccion"=>"contacto","nombre_seccion"=>TXT_MAS_INFO)); 
  $this->mostrar_web(TITULO_AVISO_LEGAL,"sel_inicio");
 }
 function politica_de_privacidad(){
  $this->c['contenido'] = $this->plantilla->devolver_contenido("contenidos/politica_de_privacidad.html");  
  $this->c['ruta'] = $this->plantilla->rellena_HTML("contenidos/ruta.html",array("ruta"=>TITULO_POLITICA." ","seccion"=>"contacto","nombre_seccion"=>TXT_MAS_INFO)); 
  $this->mostrar_web(TITULO_POLITICA,"sel_inicio");
 }
 function contacto($datos){
  $op = isset($datos[0])?$datos[0]:null;
  
  $this->c['scripts'] = zen_validaciones_js("frmContacto",$this->padre->idioma,"zenphp/contenido/",true);
  //$this->c['columna_izda1'] = $this->padre->tags->html->listar();
  switch ($op){
  	default: case null:
  	 $this->c['contenido'] = $this->plantilla->rellena_HTML("contacto/indice.html",array("mensaje"=>""));
  	 $r['ruta'] = "&raquo; ".TITULO_CONTACTO;
  	 break;
  	case 'enviar':
  	 if (!isset($_POST['nombre']) || empty($_POST['nombre']) || !isset($_POST['email'])||(empty($_POST['email']))){
  	  $this->c['contenido'] = $this->plantilla->rellena_HTML("contacto/indice.html",
  	  array("mensaje"=>TXT_RELLENE_CAMPOS));
  	 } else {
  	  //info@ruralvia.com
  	  if (zen_enviarCorreo(ZF_CORREO_ADMIN,
  	   "Contacto - inmorusticas.com",
  	  "Nombre: ".zen_sanar($_POST['nombre'],255)."<br/>\n".
  	  "E-Mail: ".zen_sanar($_POST['email'],255)."<br/>\n".
  	  "Empresa:".zen_sanar($_POST['empresa'],255)."<br/>\n".
  	  "Tel&eacute;fono:".zen_sanar($_POST['telefono'],255)."<br/>\n".
  	  "Mensaje: '".zen_sanar($_POST['mensaje'],1500)."'",
  	  zen_sanar($_POST['email'],255)))
  	   $this->c['contenido'] = $this->plantilla->devolver_contenido("contacto/enviado.html");
  	  else
  	   $this->c['contenido'] = $this->plantilla->rellena_HTML("contacto/indice.html",
  	   array("mensaje"=>TXT_ERROR_CORREO));
  	 }
  	 $r['ruta'] = "&raquo; <a href='contacto/'>".TITULO_CONTACTO."</a> &raquo; ".TXT_ENVIAR;
  	 break;
  }
  //$this->c['ruta'] = $this->plantilla->contenido_reemplaza("rutas/indice.html",$r);
  //$this->c['cabecera'] = $this->plantilla->devolver_contenido("cabeceras/cab_contacto.html");
  $this->c['titulo']   = "Contacto";
  $this->mostrar_web(TITULO_CONTACTO,"sel_contacto");
 }
 function procesar_contenido($nombre,$datos,$titulo){
 	if (is_array($datos)) array_unshift($datos,$nombre);
 	else $datos = array($nombre);
 	$this->c['contenido'] = $this->padre->contenidos->html->presenta($datos,$titulo);
 	
 	$this->c['cabecera']  = $this->plantilla->devolver_contenido("cabeceras/cab_$nombre.html");
 	$this->mostrar_web($titulo,"sel_$nombre");
 }
 
 /*function noticias($datos=null){
 	$this->procesar_contenido("noticias",$datos,"Noticias ");	
 }*/
 
 function buscar($datos){
 	if (empty($_GET['b'])) header("Location: /");
 	$frase = htmlentities(utf8_decode($_GET['b']));
 	$match = "MATCH (titulo,intro,descripcion,tags,meta_desc) AGAINST ('".$frase."')";
 	$this->padre->contenidos->campos= "idc,url_amiga,DATE_FORMAT(fecha,'%d/%m/%Y') as fecha,titulo,seccion,intro";
	$this->padre->contenidos->condiciones_where = "where $match;";
	$this->c['contenido'] = $this->padre->contenidos->html->listado("busquedas/elementos.html","busquedas/indice.html","elementos");
	$this->c['ruta'] = "<a href='/'>".TITULO_HOME."</a> &raquo; ".TITULO_BUSQUEDA;
	$this->mostrar_web(sprintf(TITULO_RESULTADOS_BUSQUEDA,$frase),"sel_inicio");
 }
 
 /**
  * Redirige la entrada si la etiqueta no es correcta o bien muestra las entradas
  *
  * @param str $datos
  */
 function etiquetas($datos=null){
   if (!isset($datos[0])) header("Location: ".ZF_SITIO_WEB);
   $datos[0] = trim(zen_sanar($datos[0]));
   if (empty($datos[0])) header("Location: ".ZF_SITIO_WEB);
   $this->padre->tags->html->ver($datos[0]);
 }
 
 function rss($datos=null){
	//Cargar la clase
	zen___carga_clase('zen_generador_rss');
	$canal_rss = new  zen_generador_rss();
	$canal_rss->enlaceAtom = '';
	$canal_rss->titulo = TITULO_RSS." ".ZF_NOMBRE_SITIO;
	$canal_rss->enlace = ZF_SITIO_WEB;
	$canal_rss->descripcion = 'Entradas actualizadas de inmorusticas.com.'; //no usar HTML o bien usar zen_borraHTML()
	$canal_rss->idioma = 'es-es';
	$canal_rss->generador = "zenPHP Generador RSS";
	$canal_rss->editor = "inmorusticas.com";
	$canal_rss->webMaster = "Programador PHP.ORG";
	//Crear los items,vale ,tenemos varios tipos de datos
	$r = $this->padre->bd->seleccion(
		'c.idc as idc,url_amiga,intro_es as intro,titulo_es as titulo,categoria,'.
		'DATE_FORMAT(fecha,"%a, %d %b %Y %H:%M:%S %z") as fecha,tipo, ca.seccion'.
		' from contenidos c INNER JOIN categorias ca ON categoria = ca.idc order by c.idc');
	while ($items = $this->padre->bd->obtener_fila($r)) {
	 $item = new zen_rss_item();
	 $item->titulo = $items['titulo'];
	 $item->descripcion = '<![CDATA['.$items['intro'].']]>';
	 //marca.seccion+'/'+marca.tipo+'/'+marca.id+"/"+marca.url_amiga
 	 $item->enlace = ZF_SITIO_WEB.$items['seccion'].'/'.$items['tipo']."/".$items['idc']."/".$items['url_amiga']."/";
	 $item->guid =& $item->enlace;
	 $item->fecha_publicacion = $items['fecha'];//strftime("%a, %d %b %Y %H:%M:%S %z"); //"Tue, 21 Feb 2008 00:00:01 GMT";
	 $canal_rss->items[] = $item;
	}
	
	$rss_feed = new zen_generador_rss();
	$rss_feed->codificacion = "UTF-8";
	$rss_feed->version = "2.0";
	header("Content-Type: text/xml");
	echo $rss_feed->crearRSS($canal_rss);
 }
 
 function registro($datos=null){
  $op = isset($datos[0])?zen_sanar($datos[0]):"";
  $le = $this->padre->idioma;
  $ti = TITULO_HOME;
  zen___carga_constantes_idiomas("registro",$le);
  switch ($op){
  	case "": default:
  		header("Location: /");
  		return "";
  		/*
  		$this->c['contenido'] = $this->plantilla->devolver_contenido("registro/indice.html");
  		*/
  		break;
  	case "crear_cuenta":
  		header("Location: /");
  		return "";
  		/*
  		if (!isset($_POST['login']) || !isset($_POST['email'])){
  		 return $this->registro();
  		}
  		//formateo de datos
  		$campos = $this->formatear_registro(array(
  		 "nombre" => array("texto",150),
  		 "contacto" => array("texto",150),
  		 "web" => array("texto", 175),
  		 "direccion" => array("texto", 600),
  		 "descripcion" => array("texto",600),
  		 "login" => array("texto",32),
  		 "rol" => array("set",array("asociacion","autoridad","ciudadano")),
  		 "email" => array("texto",175),
  		 "password" => array("texto",8)
  		));
  		if ($campos['password']!=$_POST['password_confirma']){
  		 $error = TXT_ERROR_PASSWORD;
  		}elseif ($this->padre->bd->seleccion_unica("idu from usuarios where login='".$campos["login"]."'")){
  		 $error = TXT_ERROR_LOGIN_EX;
  		} elseif ($this->padre->bd->seleccion_unica("idu from usuarios where email='".$campos["email"]."'")){
  		 $error = TXT_ERROR_EMAIL_EX;
  		} else {
  		 $campos['fecha_alta'] = "";
  		 $campos['password']   = md5($campos['password']);
  		 $us = new zen_modelo_datos($this->padre,"","usuarios");
  		 $idu= $us->insertar($campos);
  		 if (!$idu){
  		  $error = TXT_ERROR_CREANDO_US;
  		 } else {
  		  $this->c['contenido'] = $this->plantilla->devolver_contenido("registro/creado.html");
  		  $codigo = substr(md5($campos['login']),3,6).substr(md5($campos['nombre']),3,6);
		  $url = ZF_SITIO_WEB."registro/confirmar/$idu/$codigo/";
  		  if (zen_enviarCorreo($campos["email"],TXT_CUENTA_CREADA,
  		    sprintf(TXT_MAIL_CUENTA_CREADA,$url,$url),ZF_CORREO_ADMIN)){
  		    $this->c['contenido'] = TXT_CUENTA_CREADA_WEB;
  		  } else {
  		   $error = TXT_ERROR_ENVIANDO_CORREO_ALTA;
  		  }
  		 }
  		}
  		if (!empty($error)){
  		  $this->c['contenido'] = "Error: $error";
  		 }
  		 */
  		break;
  	case "confirmar":
  		if (isset($datos[1]) && isset($datos[2])){
  		 $idu = $this->padre->bd->obtener_fila_unica(
  		  $this->padre->bd->seleccion("idu,login,nombre from usuarios where idu=".intval($datos[1]))
  		 );
  		 if ($idu && (substr(md5($idu['login']),3,6).substr(md5($idu['nombre']),3,6))==$datos[2]){
  		  $this->padre->bd->actualizar("usuarios set fecha_alta = NOW() where idu=".intval($idu['idu']).
  		  	" and fecha_alta='0000-00-00 00:00:00'"); //no queremos una cuenta ya activada...
  		  $this->c['contenido'] = TXT_CUENTA_ACTIVADA;
  		 } else {
  		  print_r($datos);
  		  $this->padre->bd->mostrar_ultima_consulta();
  		 }
  		} else {
  		 $this->c['contenido'] = "Error";
  		}
  		break;
  }
  
 $this->mostrar_web($ti);
 }
 
 function &formatear_registro($datos){
  $campos = array();
  foreach ($datos as $campo => $tipo) {
  	switch ($tipo[0]){
  		case "texto":
  		 $campos[$campo] = isset($_POST[$campo])?zen_sanar($_POST[$campo],$tipo[1]):"";
  		 break;
  		case "set":
  		 $campos[$campo] = (isset($_POST[$campo])&&in_array($_POST[$campo],$tipo[1]))?$_POST[$campo]:$tipo[1][0];
  		 break;
  	}
  }
  return $campos;
 }
 
 function login($datos=null){
  $op = isset($datos[0])?zen_sanar($datos[0]):"";
  $le = $this->padre->idioma;
  zen___carga_constantes_idiomas("login",$le);
  $this->c['titulo'] = TXT_TITULO_LOGIN;
  switch ($op){
  	case "": default:
  	 $this->c['contenido'] = $this->plantilla->devolver_contenido("login/indice.html");
     break;
  	case "off":
  	 $this->padre->sesiones->destruir_sesion();
  	 $this->c['contenido'] = TXT_FIN_SESION;
  	 break;
  	case "do": //hacer login
  	 //si la fecha es del formato 2010-07-15 15:36:05 entonces ok
  	 if (!isset($_POST['login'])||!isset($_POST['password'])){
  	  return $this->login();
  	 }
  	 $idu = $this->padre->bd->obtener_fila_unica(
  	  $this->padre->bd->seleccion("idu,nombre,login,email from usuarios where login='".
  	  	zen_sanar($_POST['login'],32)."' and password=md5('".
  	  	zen_sanar($_POST['password'],8)."') and fecha_alta<>'0000-00-00 00:00:00'" //activo?
  	  )
  	 );
  	 if (!$idu){
  	  $this->c['contenido'] = TXT_ERROR_LOGIN;
  	 } else {
  	  //Crear sesión
  	  $this->padre->sesiones->iniciar();
  	  $_SESSION['idu'] = $idu['idu'];
  	  $_SESSION['login'] = $idu['login'];
  	  $_SESSION['nombre'] = $idu['nombre'];
  	  $_SESSION['email'] = $idu['email'];
  	  $this->c['contenido'] = $this->plantilla->devolver_contenido("login/ok.html");
  	 }
  	 break;
  	case "my": //mis anuncios
  	 if (!isset($_SESSION['idu'])){
  	  header("Location: /");
  	  return "";
  	 }
  	 $m =& $this->padre->markers;
  	 $l = $this->padre->idioma;
  	 $m->campos = "c.idc,url_amiga, titulo_$l, ca.seccion as seccion, intro_$l,url_amiga,tipo, if(publicado,'".TXT_SI."','".TXT_NO."') as publicado";
  	 $m->tablas = "contenidos c INNER JOIN categorias ca ON categoria = ca.idc";
  	 $m->condiciones_where = " where idu = ".$_SESSION['idu'];
  	 $this->c['contenido'] = $m->html->listado("listados/el_my.html","listados/my.html","elementos");
  	 if (!count($m->tuplas)) $this->c['contenido'] = $this->plantilla->devolver_contenido("listados/nomy.html");
  	 $this->c['titulo'] = TXT_MIS_ANUNCIOS;
  	 break;
 }
 $this->mostrar_web("");
 }
 function contactar_anunciante($datos){
  $op = isset($datos[0])?zen_sanar($datos[0]):"";
  $le = $this->padre->idioma;
  zen___carga_constantes_idiomas("contacto_anuncio",$le);
  $this->fichero_base_html = "contacto/base.html";
  $this->c['titulo'] = TXT_CONTACTO_ANU;
  switch ($op){
   case "form":
   	if (!isset($datos[1]) || !is_numeric($datos[1])) die("No idc");
   	$co = $this->padre->bd->obtener_fila_unica(
   	 $this->padre->bd->seleccion("idc,e_mail_contacto from contenidos where idc=".intval($datos[1]))
   	);
   	
   	if (!$co) die("bad idc");
    $this->c['contenido'] = $this->plantilla->rellena_HTML("contacto/anunciante.html",array(
     "idc" => $co['idc']
    ));
    break;
   case "enviar":
   	if (!isset($_POST['idc']) || !is_numeric($_POST['idc'])) break;
   	$co = $this->padre->bd->obtener_fila_unica(
   	 $this->padre->bd->seleccion("idc,nombre_contacto,e_mail_contacto from contenidos where idc=".intval($_POST['idc']))
   	);
   	if (!$co) break;
   	//enviar correo
   	if (zen_enviarCorreo($co['nombre_contacto']."<".$co['e_mail_contacto'].">",
    	sprintf(TXT_ASUNTO_MAIL_ANUN,$co['idc']),
   	    "Nombre:".zen_sanar($_POST['nombre'],150)."<br>".
   	    "Telefono:".zen_sanar($_POST['telefono'],75)."<br>".
   	    "Mensaje:".zen_sanar($_POST['mensaje'],1500)."<br>"
   		,zen_sanar($_POST['correo'],175)))
   	{
   	 $this->c['contenido'] = TXT_CONTACTO_ANU_OK;
   	} else {
   	 $this->c['contenido'] = TXT_CONTACTO_ANU_KO;
   	}
   	break;
  }
 
  echo $this->plantilla->contenido_reemplaza("contacto/base.html",$this->c);
 }
}
?>