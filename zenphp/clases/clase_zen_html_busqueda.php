<?php
/**
 * clase_zen_html_busqueda.php
 * @author Juan Belon,Jose Jiménez
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase para representar el HTML de una busqueda, usando una plantilla...
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
if (!defined('ZF_SEGURO_ZEN')) die(_("No se puede buscar...no hay zen sin zazen"));
class zen_html_busqueda {
	/**
	 * A que clase busqueda pertenece
	 *
	 * @var zen_busqueda
	 */
	var $padre;
	/**
	 * Constructor
	 *
	 * @param zen_busqueda $_padre
	 * @return zen_html_busqueda
	 */
	function zen_html_busqueda(&$_padre){
		$this->padre =& $_padre;
	}
	
	/**
	 * Pasa los resultados a la plantilla, es un modelo para ser sobrecargado en una clase busqueda
	 *
	 * @param zen_plantilla $p
	 * @param array $resultado
	 * @return boolean
	 */
	function pasarAplantilla(&$p,&$resultado){
		$p->pasarAplantilla($resultado);
		return true;
	}
	
	/**
	 * Muestra la interfaz con los resultados procesados de la busqueda. $seleccion es el/los campo/s que necesitamos obtener de la busqueda como resultado...
	 *
	 * @param str $fichero
	 * @param array $variables
	 * @param str $seleccion
	 * @param array $reemplazos
	 */
	function interfaz($fichero,&$variables,$seleccion,$reemplazos=null){
		$p = new zen_plantilla();
		if (!$p->cargar($fichero)) return _('No existe el fichero de plantilla para la b&uacute;squeda.');
		//Primero los reemplazos
		$p->pasarAplantilla($reemplazos);
		//Y luego los datos resultantes de la busqueda:
		$this->pasarAplantilla($p,$this->padre->procesarBusqueda($variables,$seleccion));
		return $p->contenido;
	}
}
?>