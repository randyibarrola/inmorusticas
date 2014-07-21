<?php
/**
 * clase_zen_calendario.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase que opera con el calendario
//TODO:generar una clase para visualizar el contenido del calendario en HTML y XML
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined('ZF_SEGURO_ZEN')) die(_('Acceso directo no permitido'));
class zen_calendario {	
	/**
	 * Aplicacion donde se usa
	 *
	 * @var zen
	 */
	var $padre;
	/**
	 * Base de datos que usa la clase
	 *
	 * @var zen_basedatos
	 */
	var $bd;
	/**
	 * Constructor de la clase
	 *
	 * @param zen $_padre
	 * @return zen_correo
	 */
	function zen_calendario(&$_padre){
		$this->padre =& $_padre;
		$this->bd    =& $this->padre->bd;
	}
	
	/**
	 * Formato para DS -> dia/mes/año
	 * Formato para los demás ...
	 *
	 * @param str $quecosa
	 * @param date $_fecha
	 * @param str $idioma : codigo del idioma ,el directorio dentro de DIR_IDIOMAS
	 * @return str||date
	 */
	function parsearFechaTexto($quecosa='t',$_fecha="",$idioma=IDIOMA_DEFECTO) { //t = toda la fecha
		require_once(DIR_IDIOMAS.$idioma.'/fecha.php');
		$diassemana = array(TEXT_DOMINGO,TEXT_LUNES,TEXT_MARTES,TEXT_MIERCOLES,TEXT_JUEVES,TEXT_VIERNES,TEXT_SABADO) ;
		$mesesano = array(TEXT_ENERO,TEXT_FEBRERO,TEXT_MARZO,TEXT_ABRIL,TEXT_MAYO,
			TEXT_JUNIO,TEXT_JULIO,TEXT_AGOSTO,TEXT_SEPTIEMBRE,TEXT_OCTUBRE,
			TEXT_NOVIEMBRE,TEXT_DICIEMBRE);
		switch ($quecosa) {
		case 'd':
			return $diassemana[date('w')];
			break;
		case 'dm':
			return date('j') ;
			break;
		case 'm':
			return $mesesano[date('n')] - 1 ;
			break;
		case 'y':
			return date('Y') ;
			break;
		case 'DS': //Dia de la semana de una fecha en concreto
			$fecha = split('/',$_fecha);
			return  $diassemana[date('w',mktime(0,0,0,$fecha[1],$fecha[0],$fecha[2]))]." ".$fecha[0]." ".TEXT_DE." ".
					$mesesano[intval($fecha[1])]." ".TEXT_DEL." ".$fecha[2];
			break;
		default: //toda la fecha completa
			return $diassemana[$diassemana[date('w')]].",".date('j')."/".($mesesano[date('n')] - 1)."/".date('Y');
			break;
		}
	}
}
?>