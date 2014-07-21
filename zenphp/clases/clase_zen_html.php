<?php
/**
 * clase_zen_html.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase para controlar/generar HTML en la clase zen
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined('ZF_SEGURO_ZEN')) die(_('Sin HTML no hay dolor.Acceso directo no permitido'));
class zen_html {
	/**
	* Clase plantilla utilizada para mostrar el HTML
	*
	* @var zen_plantilla
	*/
	var $plantilla;
	/**
	 * La aplicación padre
	 *
	 * @var zen
	 */
	var $padre;
	/**
	 * Plantilla HTML a usar por defecto
	 *
	 * @var str
	 */
	var $fichero_base_html ='base_web.html';
	/**
	 * Constructor del visualizador por defecto
	 *
	 * @param zen $_padre
	 * @return zen_html
	 */
	function zen_html(&$_padre){
		$this->padre =& $_padre;
		if (zen___carga_clase('zen_plantilla'))
			$this->plantilla = new zen_plantilla();
	}
	
	/**
	 * Muestra el $contenido 
	 *
	 * @param array $contenido
	 */
	function mostrar(&$contenido,$fichero_base=""){
		/** TODO: Reemplazar el echo por varios que vayan cortándolo para hacerlo más eficiente */
		if (!is_array($contenido)) $contenido = serialize($contenido);
		$contenido['ZF_SITIO_WEB'] = ZF_SITIO_WEB;
		$contenido['ZF_VERSION']   = ZF_VERSION;
		$contenido['ZF_DIR_CONTENIDO_ZEN'] = ZF_SITIO_WEB.str_replace("//","/",str_replace(ZF_DIR_PPAL,"",ZF_DIR_CONTENIDO_ZEN));
		$contenido['ZF_NOMBRE_SITIO']  = ZF_NOMBRE_SITIO;
        //Las rutas de windows han de pasar a formato linux para que el navegador las entienda:
        $contenido['ZF_DIR_CONTENIDO_ZEN'] = str_replace("\\","/",$contenido['ZF_DIR_CONTENIDO_ZEN']);
		//utf8_encode
        echo ($this->plantilla->contenido_reemplaza(empty($fichero_base)?$this->fichero_base_html:$fichero_base,$contenido));
	}
	/**
	 * Controlador por defecto
	 *
	 */
	function index(){
		$this->mostrar($this->padre->contenido);
	}
}
?>
