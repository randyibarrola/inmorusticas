<?php
/**
 * clase_zen_html_admin.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase para controlar/generar HTML en la clase zen_aplicacion_admin
 */
/* +----------------------------------------------------------------------
   |                              __                
   |                             /\ \               
   |  ____      __    ___   _____\ \ \___   _____   
   | /\_ ,`\  /'__`\/' _ `\/\ '__`\ \  _ `\/\ '__`\ 
   | \/_/  /_/\  __//\ \/\ \ \ \L\ \ \ \ \ \ \ \L\ \
   |   /\____\ \____\ \_\ \_\ \ ,__/\ \_\ \_\ \ ,__/
   |   \/____/\/____/\/_/\/_/\ \ \/  \/_/\/_/\ \ \/ 
   |                          \ \_\           \ \_\ 
   |                           \/_/            \/_/ 
   |
   |http://www.zenphp.es
   +----------------------------------------------------------------------*/
if (!defined('ZF_SEGURO_ZEN')) die(_('Acceso directo no permitido'));

class zen_html_admin extends zen_html {
	/**
	 * Padre: administrador
	 *
	 * @var zen_aplicacion_admin
	 */
	var $padre;
	/**
	 * Sobrecarga de la plantilla base HTML por defecto:
	 *
	 * @var str
	 */
	var $fichero_base_html = "admin/base_web.html";
	/**
	 * Constructor
	 *
	 * @param zen_aplicacion_admin $padre
	 * @return zen_html_admin
	 */
	function zen_html_admin(&$padre){
	 parent::zen_html($padre);
	}
	//Sobrecarga de las funciones HTML para administrador:
	/**
	 * Muestra el $contenido 
	 *
	 * @param array $contenido
	 */
	function mostrar(&$contenido,$fichero_base=""){ //-->/media/plantillas/admin/base_web.html
	 $contenido['ZF_SITIO_WEB'] = ZF_SITIO_WEB;
	 echo $this->plantilla->rellena_HTML(empty($fichero_base)?$this->fichero_base_html:$fichero_base,$contenido);
	}
	/**
	 * Controlador por defecto
	 *
	 */
	function index(){
	 $this->mostrar($this->padre->contenido);
	}
	/**
	 * Función a sobrecargar para hacer el login
	 *
	 */
	function login(){
	 if ($this->padre->login($_REQUEST['usuario'],$_REQUEST['password'])){
	  //Operaciones extra además de crear la sesión que es automática y redirigir a index.php que también lo es...
	  $this->padre->contenido['contenido'] = _("Login ok");
	 } else {
	  $this->padre->contenido['contenido'] = _("Login incorrecto");
	 }
	 parent::index();
	}
}
?>