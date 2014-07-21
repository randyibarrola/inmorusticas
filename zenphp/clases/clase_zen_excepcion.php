<?php
/**
 * clase_zen_excepcion.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase para controlar/generar excepciones de PHP
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined('ZF_SEGURO_ZEN')) die(_('Acceso directo no permitido'));
/**
 * Clase de Excepciones
 *
 * @package		zenphp
 * @subpackage	Clases
 * @category	Excepciones
 * @author	Juan Belon
 * @link	http://www.zenphp.es
 * @link 	https://forja.rediris.es/projects/csl2-zenphp/
 */
class zen_excepcion {
	/**
	 * Accion a tomar...
	 *
	 * @var str
	 */
	var $accion;
	/**
	 * Severidad de la excepcion
	 *
	 * @var str
	 */
	var $severidad;
	/**
	 * Mensaje de la excepcion
	 *
	 * @var str
	 */
	var $mensaje;
	/**
	 * Fichero que la ha producido
	 *
	 * @var str
	 */
	var $nombre_fichero;
	/**
	 * Linea en la que se encontró
	 *
	 * @var int
	 */
	var $linea;
	/**
	 * Nivel de profundidad de objeto
	 *
	 * @var int
	 */
	var $nivel_ob;
	/**
	 * Mensajes para cada nivel, sin idiomas porque aun no se ha definido, es la base!
	 *
	 * @var array
	 */
	var $niveles;
	/**
	 * Colores del fondo de la division para el mensaje de error devuelto o mostrado...
	 * formato: CSS
	 *
	 * @var array
	 */
	var $colores = array(
		E_ERROR				=>	'border: 1px raised red; background-color: pink',
		E_WARNING			=>	'border: 1px dotted red; background-color: silver',
		E_PARSE				=>	'border: 1px dotted red; background-color: silver',
		E_NOTICE			=>	'border: 1px dotted red; background-color: silver',
		E_CORE_ERROR		=>	'border: 1px dotted red; background-color: pink',
		E_CORE_WARNING		=>	'border: 1px dotted red; background-color: pink',
		E_COMPILE_ERROR		=>	'border: 1px dotted red; background-color: red',
		E_COMPILE_WARNING	=>	'border: 1px dotted red; background-color: #E9D3AE', //amarillo
		E_USER_ERROR		=>	'border: 1px dotted red; background-color: silver',
		E_USER_WARNING		=>	'border: 1px dotted red; background-color: silver',
		E_USER_NOTICE		=>	'border: 1px dotted red; background-color: yellow',
		E_STRICT			=>	'border: 1px dotted red; background-color: silver'
	);
	/**
	 * Fichero de icono que representa el error , estan en /zen_php/img/
	 *
	 * @var array
	 */
	var $iconos = array(
		E_ERROR			=>	'e_error.png',
		E_WARNING		=>	'e_warning.png',
		E_PARSE			=>	'e_parse.png',
		E_NOTICE		=>	'e_notice.png',
		E_CORE_ERROR		=>	'e_core_error.png',
		E_CORE_WARNING		=>	'e_core_warning.png',
		E_COMPILE_ERROR		=>	'e_compile_error.png',
		E_COMPILE_WARNING	=>	'e_compile_warning.png',
		E_USER_ERROR		=>	'e_user_error.png',
		E_USER_WARNING		=>	'e_user_warning.png',
		E_USER_NOTICE		=>	'e_user_notice.png',
		E_STRICT		=>	'e_strict.png'
	
	);
	/**
	 * Clase que guarda los logs en ficheros
	 *
	 * @var zen_error_log
	 */
	var $error_log;
	/**
	 * Constructor
	 *
	 */	
	function zen_excepcion()
	{
		$this->nivel_ob = ob_get_level();
		$this->niveles  = array(
		E_ERROR				=>	_('Error'),
        E_WARNING			=>	_('Alerta'),
		E_PARSE				=>	_('Error de interprete'),
		E_NOTICE			=>	_('Aviso'),
		E_CORE_ERROR		=>	_('Error de nucleo'),
		E_CORE_WARNING		=>	_('Alerta de nucleo'),
		E_COMPILE_ERROR		=>	_('Error al compilar'),
		E_COMPILE_WARNING	=>	_('Alerta al compilar'),
		E_USER_ERROR		=>	_('Error de usuario'),
		E_USER_WARNING		=>	_('Alerta de usuario'),
		E_USER_NOTICE		=>	_('Aviso de usuario'),
		E_STRICT			=>	_('Aviso en tiempo de ejecucion')
	);
		if (ZF_MODO_GUARDAR_LOG==true){
			zen___carga_clase('zen_error_log');
			$this->error =& new zen_error_log('error_log-'.date('Y-m-d').".log",'zenphp');
		}
	}
  	
	// --------------------------------------------------------------------

	/**
	 * Logeador de excepciones
	 *
	 * Guarda los mensajes de log PHP de las excepciones
	 *
	 * @access	private
	 * @param	str  severidad del error
	 * @param	str	mensaje de error
	 * @param	str	nombre del fichero
	 * @param	str	linea dentro del fichero
	 * @return	str
	 */
	function logear_excepcion($severidad, $mensaje, $nombre_fichero, $linea)
	{	
		$severidad= ( ! isset($this->niveles[$severidad])) ? $severidad: $this->niveles[$severidad];
		if (ZF_MODO_GUARDAR_LOG){
			
		}
	}

	// --------------------------------------------------------------------
	/**
	 * Manejador de un error de PHP nativo
	 *
	 * @access	private
	 * @param	str	_severidad del error
	 * @param	str	cadena del error
	 * @param	str	nombre del fichero del error
	 * @param	str	linea del error en el fichero
	 * @return	str
	 */
	function mostrar_error_php($_severidad, $mensaje, $nombre_fichero, $linea)
	{	        
		$severidad = ( ! isset($this->niveles[$_severidad])) ? $_severidad: $this->niveles[$_severidad];
	
		$nombre_fichero = str_replace("\\", DIRECTORY_SEPARATOR, $nombre_fichero);
		
		// No mostraremos toda la ruta para que no se intente juankear nada
		if (false !== strpos($nombre_fichero, DIRECTORY_SEPARATOR))
		{
			$x = explode(DIRECTORY_SEPARATOR, $nombre_fichero);
			$nombre_fichero = $x[count($x)-2].'/'.end($x);
		}
		
		if (ob_get_level() > $this->nivel_ob + 1)
		{
			ob_end_flush();	
		}
		ob_start(); 
        
        $icon = isset($this->iconos[$_severidad]) ? $this->iconos[$_severidad] : $this->iconos[1];
        $color = isset($this->colores[$_severidad]) ? $this->colores[$_severidad] : $this->colores[1];
        
	
		$buffer = ob_get_contents();
		ob_end_clean();
		if (!zen___carga_clase('zen_plantilla')) die(_('No se pudo cargar la clase de plantillas'));
		$p =& new zen_plantilla();
                $gtk = defined('ZF_MODO_GTK')?ZF_MODO_GTK:false;
		//Usa la plantilla general en /zen_plantillas/zen_general/zen_errores/zen_error_php.html
                if (!$gtk && $p -> cargar('zen_error_php.html',ZF_DIR_CONTENIDO_ZEN.'zen_general'.DIRECTORY_SEPARATOR.'zen_errores'.DIRECTORY_SEPARATOR)) {
			$p->pasarAplantilla(array(
			'severidad' => $severidad,
			'mensaje'   => $mensaje,
			'linea'     => $linea,
			'nombre_fichero' => $nombre_fichero,
			'ZF_SITIO_WEB' => ZF_SITIO_WEB,
			'logo'=> "zenphp/contenido/img/logo.jpg",
			'ZF_NOMBRE_SITIO'=>ZF_NOMBRE_SITIO,
			//'buffer' => $buffer,
			'icono' => ZF_SITIO_WEB.'zenphp/contenido'.
					   "/img/". $icon ,
			'colores' => $color
			));
			echo $p->contenido;
		} else {
                echo sprintf(_("\nError PHP:\n")).
				_('Severidad:') . $severidad."\n".
				_('Mensaje:')   . $mensaje."\n".
				_('Fichero:')   . $nombre_fichero."\n".
                                _('Linea:')      .$linea."\n"
				//.'buffer' . $buffer
				;
		}
	}
}
?>