<?php
/**
 * clase_zen.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase que contiene el sistema del que cuelgan las demás clases y aplicaciones...
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined('ZF_SEGURO_ZEN')) die(_('Acceso directo no permitido'));
if (class_exists('clase_zen')) return true; //Se cargo antes?
 //Podemos cargar todas las clases de las que depende la principal?
if (!zen___carga_clase('zen_basedatos') ) die(_('No se pudo cargar la clase para operar con la base de datos zen_basedatos'));
if (!zen___carga_clase('zen_sesiones_seguras') ) die(_('No se pudo cargar la clase para operar con sesiones de php zen_sesiones_seguras'));
if (!zen___carga_clase('zen_html') ) die(_('No se pudo cargar la clase para operar con la salida HTML'));
//Por ahora retiramos esta carga
//if (!zen___carga_clase('zen_config')) dieG('No se pudo cargar la clase de config zen');
/**
 * Esta es la clase base padre de todas las clases del sistema _base_
 *
 */
class zen {
	/**
	 * Lenguage utilizado en toda la web
	 *
	 * @var str
	 *
	*/
	var $idioma;
	/**
	 * clase_sesiones_seguras
	 *
	 * @var zen_sesiones_seguras
	 */
	var $sesiones;
	/**
	 * Clase de base de datos
	 *
	 * @var zen_basedatos
	 */
	var $bd;
 	/**
 	 * Clase para enviar correos en UTF-8 y HTML
 	 *
 	 * @var zen_correo
 	 */
 	var $correo;
 	/**
 	 * clase de operaciones con login
 	 *
 	 * @var zen_login
 	 */
 	var $login;
 	/**
 	 * Clase para el visualizador por defecto
 	 *
 	 * @var zen_html
 	 */
 	var $html;
 	/**
 	 * Clase para cargar configuraciones
 	 *
 	 * @var zen_config
 	 */
 	//var $config;
 	/**
 	 * Contenido de la página web en un array, lo que se pasará a los procesadores de Vistas ;)
 	 *
 	 * @var array
 	 */
 	var $contenido;
 	/**
	 * Una instancia zen_enrutador si la opcion ZF_USAR_ENRUTADOR es true
	 *
	 * @var zen_enrutador
	 */
	var $enrutador;
	/**
	 * Para manejar esta clase hemos de comprobar que ZF_MANEJADOR_BUFFER=='zen_buffer' ya que ,
	 * el búfer de PHP podria estar siendo manejado por otro tipo de función,como 'ob_gzhandler'
	 * en cuyo caso no existe la clase $this->buffer
	 *
	 * @var zen_buffer
	 */
	var $buffer;
	/**
	 * Clase Constructor de la clase base para crear aplicaciones, usa $inicializadores de clases
	 *
	 * @param str $inicializadores
	 * @return zen
	 */
	function zen($inicializadores=""){ //CONSTRUCTOR	
		//Atención: lo que queremos son punteros!
		static $contenido  = array();
		$this -> contenido =& $contenido; //para poder modificarlos!!
		if (!defined('ZF_MANEJADOR_BUFFER')){
			if (!class_exists('zen_buffer')) {
				if (zen___carga_clase('zen_buffer')){
					$this->buffer =& new zen_buffer();
					define('ZF_MANEJADOR_BUFFER','zen_buffer');
				}
			}
		}
		$this->sesiones =& new zen_sesiones_seguras($this);
		$this->sesiones->comprueba_navegador = true;
  		$this->sesiones->num_bloques_ip      = 2;
  		$this->sesiones->palabra_secreta     = ZF_PALABRA_SECRETA; //definida en /zen.php
  		$this->sesiones->regenerar_id        = true;
  		/**
  		 * Posible uso de la clase de sesiones seguras:
  		 *
  		 */
  		/*
  		//----------comprobacion-----
  		if (!$this->sesiones->comprobar_sesion() || !isset($_SESSION['logeado']) || !$_SESSION['logeado'])
  		{
    		header('Location: login.php');
    		die();
  		}
  		//----------formulario de login----
		$this->sesiones->iniciar();
		$_SESSION['logeado'] = true;
		header('Location: index.php'); 
  		*/  		
		$this->comprobarLenguaje(ZF_IDIOMA_COOKIE,ZF_DIR_PLANTILLAS,ZF_IDIOMA_DEFECTO);
		//Base de datos
		$this->bd =& new zen_basedatos();
		/*
		 Cargar clases: zen_login,zen_plantilla,zen_correo,zen_modelo_datos + los que se quiera cargar...
		 Nota importante: el orden de creación de las clases es el indicado,
		 en otro caso podria no funcionar correctamente: zen_config
		*/
		if (!empty($inicializadores))
		 $this->inicializar($inicializadores);
		//FIX: evitar uso de eval
  		//zen___carga_clase("zen_login");
		zen___carga_clase("zen_html");
		zen___carga_clase("zen_modelo_datos");
		$this->html =& new zen_html($this);
		//Enrutador:
		if (ZF_USAR_ENRUTADOR){
			zen___carga_clase('zen_enrutador');
			//Los controladores por defecto se encontrarán en el directorio de clases...
			$this->enrutador =& new zen_enrutador($this);
		}
		//Destructor de la clase:
		register_shutdown_function(array( &$this, "destructor" ));
	}
	/**
	* Llama a los constructores,se pueden pasar las clases a crear e inicializar en un array de cadenas o en una cadena
	* separada por comas, de forma que se carga la clase con dicho nombre y se añade como variable de la clase
	* principal.
	* 
	* juaxix dice:
	* La programacion es "Estando en la accion",es puro zen....asi, se puede saber que es zen...
	* el zen no se puede entender por las palabras
	* pero si por la practica
	* dentro del zen...todo es zen...
	* asi es
	* solo son palabras
	* el codigo es 1 poco mas q palabras
	* es la accion
	* la accion es zen,solo accion
	* y zazen es solo vacio
	* pero las 2 cosas son la misma cosa
	* antonio dice: matrix y no matrixx se confunden...
	*
	* @param array('Clase1,Clase2,...') $matrix
	*/
	function inicializar($matrix) {
		if (!is_array($matrix)) $matrix = split(",",$matrix);
		$n = sizeof($matrix);		
		
		for ($i=0; $i<$n; $i++){
			if (empty($matrix[$i])) {
				continue;
			}
			if (!zen___carga_clase( $matrix[$i])) die(printf(_('No se pudo cargar la clase %s'),$matrix[$i]));
			//Si es del nucleo le quitamos el apostrofe para meter la clase con su nombre tal cual
			$variable=(substr($matrix[$i],0,4)=='zen_')?substr($matrix[$i],4,strlen($matrix[$i])-4):$matrix[$i];
			//Creamos la clase y la metemos en el contenedor
			eval('$this->'.$variable.' =& new '.$matrix[$i].'($this);');
		}
	}
	
	/**
	 * Comprueba el lenguaje al que se cambia,si no existe para la compilacion PHP y devuelve el resultado.
	 * Si el directorio del idioma no existe se detiene la ejecución del sistema.
	 *
	 * @param str $variable
	 * @param str $dir_lenguajes
	 * @param str $idi_defecto
	 * @return bool si el idioma al que se cambia esta bien definido...
	 */
	function comprobarLenguaje($variable='len',$dir_lenguajes,$idi_defecto){
		//Comprobar el lenguaje:
		$salida = true;
		$len = (isset($_GET[$variable]) && !empty($_GET[$variable])?zen_sanar($_GET[$variable]):(
			   (isset($_POST[$variable]) && !empty($_POST[$variable])?zen_sanar($_POST[$variable]):
			   (isset($_COOKIE[$variable]) && !empty($_COOKIE[$variable])?zen_sanar($_COOKIE[$variable]):zen_detectar_idioma_navegador($idi_defecto)))));
		$len = strtolower($len);
		if (in_array($len,explode(",",ZF_IDIOMAS)) && is_dir($dir_lenguajes.$len)){
		 $this->idioma = $len;		 
		 if (!defined('ZF_DIR_IDIOMA'))
		  define('ZF_DIR_IDIOMA',$dir_lenguajes.$len.DIRECTORY_SEPARATOR);
		} else  {
		 define('ZF_DIR_IDIOMA',$dir_lenguajes.$idi_defecto.DIRECTORY_SEPARATOR);
		 $this->idioma = $idi_defecto;
		 $salida = false;
		 if ($len!=$idi_defecto)
		  header("Location: ".ZF_SITIO_WEB."index.php?len=".$idi_defecto);
		 else
		  die(_('El lenguaje no existe'));
		}
		//Establecer cookie de lenguaje
		if (!isset($_COOKIE[$variable]) || $_COOKIE[$variable]!=$len)
		 setcookie($variable,$this->idioma,time()+4600); //+ de 1hora cada vez
		return $salida;
	}
	/**
	 * Destructor de la clase base zen
	 *
	 */
	function destructor(){
		$this->bd->desconectar();
	//Fix: sólo para navegadores con soporte HTTP1.1
	//header('Content-Length: ' . ob_get_length());
		ob_end_flush();
	}
}
?>