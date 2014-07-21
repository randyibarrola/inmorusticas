<?php
/**
 * @file zen_nucleo.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @brief Conjunto de funciones del núcleo de zenphp sin las que no funciona zenphp
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
/**
* Construye la clase en modo singleton
*
* @param str $clase
* @return $clase
*/
function &zen_singleton($clase) {
	static $instancias;
	if (!class_exists($clase)){ //Está cargada la clase?
		if (!zen___carga_clase($clase)) die(printf(_('No se pudo cargar la clase %s'),$clase));
	}
	if (!is_array($instancias)) {
		$instancias = array();
	}
	if (!isset($instancias[$clase]) || !is_object($instancias[$clase]) || !is_a($instancias[$clase],$clase)) {
		$instancias[$clase] =& new $clase;
	}
	return $instancias[$clase];
}
/**
=============================================================================
Funcion: detectar_idioma_navegador()
-----------------------------------------------------------------------------
Proposito: Esta funcion detecta el idioma por defecto seleccionado en el navegador del visitante.
Si no se detecta un lenguaje valido, se asigna el selecionado por defecto.
=============================================================================
* @param str $defecto : el codigo del idioma por defecto
*/
function zen_detectar_idioma_navegador($defecto=ZF_IDIOMA_DEFECTO) {	
	$idiomas=explode(",",trim(ZF_IDIOMAS));
	$idioma = preg_replace('/(;q=\d+.\d+)/i', '', getenv('HTTP_ACCEPT_LANGUAGE'));
	//es-es,es,en-us,en
	$idioma_detectado = false;	
	// Comprobamos si el navegador usa alguno de los idiomas que hemos predefinido.
	foreach ($idiomas as $matriz_aux) {
		if (preg_match( '/' . $matriz_aux . '/i', $idioma)) {
			$idioma_aux = $matriz_aux;
			$idioma_detectado = true;
			break;
		}
	}
	// Si el navegador usa uno de los idiomas seleccionados, se devuelve el path del fichero de idioma
	// En caso contrario, se devuelve el path del idioma original
	if ($idioma_detectado) {
		return $idioma_aux;
	} else {
		return $defecto;
	}
}
/**
* Carga el fichero de funciones con el nombre pasado
* @param str $fichero 
* @return bool
*/
function zen___carga_funciones($fichero){
	//Comprobar la extensión:
	if (substr(strtolower($fichero),-4,4)!=".php") $fichero = $fichero.".php";
	if (file_exists(ZF_DIR_FUNCIONES.$fichero)){ 
	 //Es un fichero de funciones de usuario colocado en el 
	 //directorio de zenphp/funciones/?
	 include_once(ZF_DIR_FUNCIONES.$fichero);
	 return true;
	} else {
	  //Error: no existe el fichero
	  return false;
	}
}
/**
 * Carga la clase pasada intentando buscar en los directorios de clases,el actual, el de clases ,librerias y el pasado como argumento
 * incluso con clases/
 *
 * @param str $clase
 * @param str $directorio donde buscar la clase, se prueba tambien con $directorio/clases/clase_$clase donde clase es ZF_PREFIJO_CLASE
 * @return bool
 */
function zen___carga_clase($clase,$directorio="",$mostrar_error=false){
	//Hemos cargado previamente la clase?
	if (class_exists($clase)) return true;
	//Construir el nombre de fichero como con las funciones
	if (substr(strtolower($clase),-4,4)!=".php") $clase = $clase.".php";
	//Lleva el prefijo clase_?
	if (strpos($clase,ZF_PREFIJO_CLASE,0)===false)//Probar con el prefijo:
	 if (zen___carga_clase(ZF_PREFIJO_CLASE."_".$clase,$directorio,false))
		return true;
	//if (substr(strtolower($clase),0,6)!=ZF_PREFIJO_CLASE."_") $clase = ZF_PREFIJO_CLASE."_".$clase;
	//Existe la clase?
	if (!file_exists(ZF_DIR_CLASES.$clase)) {
		//Y si si fuera admin y la carpeta de clases estuviera dentro?
		if (file_exists(ZF_PREFIJO_CLASES.DIRECTORY_SEPARATOR.$clase))
		{
			require_once(ZF_PREFIJO_CLASES.DIRECTORY_SEPARATOR.$clase);
			return true;
		} else {
			//Entonces solo pueden estar en el directorio de librerias:
			if (file_exists(ZF_DIR_LIBRERIAS.ZF_PREFIJO_CLASE.$clase)){
				require_once(ZF_DIR_LIBRERIAS.ZF_PREFIJO_CLASE.$clase);
				return true;
			}
			else {
				if (file_exists(ZF_DIR_LIBRERIAS.$clase)) {
					//Sin prefijo? mal hecho pero es posible que este
					require_once(ZF_DIR_LIBRERIAS.$clase);
					return true;
				}
				else {
					if (!empty($directorio)){
					 if (is_readable($directorio.DIRECTORY_SEPARATOR.$clase)){
					   require_once($directorio.DIRECTORY_SEPARATOR.$clase);
					   return true;
					 } else {
					 	if (is_readable($directorio.DIRECTORY_SEPARATOR.ZF_PREFIJO_CLASES.DIRECTORY_SEPARATOR.$clase)){
					 	 require_once($directorio.DIRECTORY_SEPARATOR.ZF_PREFIJO_CLASES.DIRECTORY_SEPARATOR.$clase);
					 	 return true;
					 	} else {
                         if ( $mostrar_error ) trigger_error(sprintf(_("No se puede cargar la clase %s/%s"),$directorio,$clase),E_USER_ERROR);
					  	 return false; //Si no pues es un error
					 	}
					 }
					} else {
						if ( $mostrar_error ) trigger_error(_("No se puede encontrar el fichero de la clase ")."($clase)",E_USER_ERROR);
						return false;
					}
				}
			}
		}
	}
	else { //Solo queda cargar la clase
		require_once(ZF_DIR_CLASES.$clase);
		return true;
	}
}
/**
 * Carga la clase del directorio de modelos
 *
 * @param str $nombre
 * @param bool $mostrar_error
 * @return bool
 */
function zen___carga_modelo($nombre,$mostrar_error=false){
	return zen___carga_clase($nombre,ZF_DIR_APLICACIONES.'modelos',$mostrar_error);
}
/**
 * Carga la clase del directorio de vistas(un visualizador)
 *
 * @param str $nombre
 * @param bool $mostrar_error
 * @return bool
 */
function zen___carga_vista($nombre,$mostrar_error=false){
	return zen___carga_clase($nombre,ZF_DIR_APLICACIONES.'vistas',$mostrar_error);
}
/**
 * Carga una aplicacion de usuario,nombre del fichero sin php
 *
 * @param  $nombre
 * @return bool
 */
function zen___carga_aplicacion($nombre){    
	//Preparamos el sistema para cargar aplicaciones ahora
	if (!strpos($nombre,"admin")){
	 if (!zen___carga_clase('zen_aplicacion')) die(_('No se pudo cargar la clase zen_aplicacion'));
	} elseif (!zen___carga_clase('zen_aplicacion_admin')) die(_('No se pudo cargar la clase zen_aplicacion_admin'));
	//Es necesario cargar el modelo de datos porque es lógico que se quiera utilizar,una aplicación sin datos no tiene sentido XD
	if (!zen___carga_clase('zen_modelo_datos')) die(_('No se pudo cargar la clase zen_modelo_datos'));
	if (!zen___carga_clase('zen_html')) die(_('No se pudo cargar la clase zen_html'));
	if (strtolower(substr($nombre,-4,4))!='.php') $nombre.= ".php";
	if (!is_readable(ZF_DIR_APLICACIONES.$nombre)) die(printf(_('No existe el fichero de aplicaci&oacute;n %s'),$nombre));
	else require_once(ZF_DIR_APLICACIONES.$nombre);
	return true;
}
/**
 * Comprueba que existe el fichero de constantes en el directorio de constantes de idiomas ( /media/idiomas/$idioma/$fichero )
 *
 * @param str $fichero
 * @param str $idioma
 * @return bool
 */
function zen___carga_constantes_idiomas($fichero,$idioma=ZF_IDIOMA_DEFECTO){
	$constantes = ZF_DIR_IDIOMAS.$idioma.DIRECTORY_SEPARATOR.$fichero;
	if (file_exists($constantes) && is_readable($constantes)){
		//Cargar las constantes de idiomas
		require_once($constantes);
		return true;
	} else {
		//Añadir la extensión .php para comprobar si existe:
		if (substr(strtolower($fichero),-4,4)!='.php') {
			$fichero .= '.php';
			$constantes = ZF_DIR_IDIOMAS.$idioma.DIRECTORY_SEPARATOR.$fichero;
		}
		if (is_readable($constantes)){
			require_once($constantes);
			return true;
		} else {
			return false;
		}
	}
}
/**
* Manejador de excepciones
*
* @brief Controla los errores desde el principio de la carga del sistema,
* asi podemos registrarlos ya que es posible que no podamos tener
* acceso a los logs del servidor. Se usan plantillas para mostrarlos.
*
* @access	private
* @return	void
*/
function zen__manejador_de_errores($severidad, $mensaje, $ruta_fichero, $linea)
{	
	// No queremos errores estrictos (deprecated y otros)
	if ($severidad == E_STRICT)
	{
		return null;
	}
		
	if (!zen___carga_clase('zen_excepcion')) die(_('No se pudo cargar la clase de excepciones'));
	$errores =& new zen_excepcion();
	// Determinamos si hemos de mostrar el error con una mascara a partir de la severidad AND error_reporting()
	if (($severidad & error_reporting()) == $severidad)
	{
		$errores->mostrar_error_php($severidad, $mensaje, $ruta_fichero, $linea);
	}
	
	// Debemos guardar el error?  No?  Listo
	
	if (ZF_MODO_DEPURACION)
	{
		$errores->logear_excepcion($severidad, $mensaje, $ruta_fichero, $linea);
	}
	
}
/**
* Función para definir sólo una vez una constante, comprueba que no existe y devuelve el resultado
* Recordar que no se pueden definir arrays como constantes 
* @param str $constante
* @param unknow $valor
*/
function zen_definir_una_vez($constante,$valor){
	if (is_array($valor)) return false;
	$definida = defined($constante);
	if (!$definida) {
	 @define($constante,$valor);
	 return defined($constante);
	}
	return $definida;
}
/**
* Función para cargar varios modelos en una aplicación.
* Se puede utilizar el comodín asterisco para que se carguen.
* El parámetro $cargar_en_aplicacion crea una instancia con el nombre de la clase dentro de la aplicación
* @param zen $aplicacion
* @param str $clases
* @param bool $cargar_en_aplicacion
* @param array $prohibidos sólo tiene efecto para cuando no se especifican la lista de clases a cargar,array de palabras que contienen los nombres de las clases que no queremos cargar
* @return bool
*/
function zen___importar_modelos(&$aplicacion, $clases="",$cargar_en_aplicacion=true,$prohibidos = array('admin')){
 if (!is_a($aplicacion,"zen")) die("El primer par&aacute;metro de zen___importar_modelos no es una clase zen");
 if (!empty($clases)){
  if (!is_array($clases)) $clases=explode(",",trim($clases));
  $n = count($clases);
  for ($i=0; $i<$n; $i++){
  	if (zen___carga_modelo($clases[$i],false)){
  		if ($cargar_en_aplicacion){
  			//Intenta cargar el modelo en la aplicación:
  			@$aplicacion->$clases[$i] =& new $clases[$i]($aplicacion);
  		}
  	}
  }
 } else {
  //Recorrer todos los modelos disponibles:
  $ruta = ZF_DIR_APLICACIONES.'modelos'.DIRECTORY_SEPARATOR;
  $dir = opendir($ruta);
  if (!$dir){
  	trigger_error(_("No existe el directorio de modelos ").$ruta,E_ERROR);
  	return false;
  }
  while ($f = readdir($dir)) {
  	$n = count($prohibidos);
  	$esta = false; //no está prohibido el fichero
  	for ($i=0; $i<$n; $i++){
	 if (strpos($f,$prohibidos[$i])) {$esta = true; break; } //Prohibido
  	}
  	if ($esta) continue;
  	if ($f!='.' && $f!='..'){
  		//cargar modelo:
  		require_once($ruta.$f);
  		$clase = str_replace(array("clase_",".php"),"",$f);
  		if ($cargar_en_aplicacion){
  			//Intenta cargar el modelo en la aplicación:
  			$aplicacion->$clase =& new $clase($aplicacion);
  		}
  	}
  }
  closedir($dir);
 }
 
 
 return true;
}
?>