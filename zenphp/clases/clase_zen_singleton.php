<?php
/**
 * clase_zen_singleton.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase para crear clases que heredan de esta y sirve para evitar múltiples instancias. i.e.: una única instancia por cada new()
 * @see zen
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined('ZF_SEGURO_ZEN')) die(_('Acceso directo no permitido'));
//
/**
* Construye la clase en modo singleton
*
* @param str $clase
* @return $clase
*/
function &zen_singleton($clase) {
	static $instancias;
	if (!is_array($instancias)) {
		$instancias = array();
	}

	if (!isset($instancias[$clase]) || !is_object($instancias[$clase]) || !is_a($instancias[$clase],$clase)) {
		$instancias[$clase] =& new $clase;
	}

	return $instancias[$clase];
}

?>
