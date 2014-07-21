<?php
/**
 * clase_zen_cronometro.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Una clase PHP simple para medir el tiempo de carga del sistema
 */
if (!defined('ZF_SEGURO_ZEN')) die(_("1,2,3,...para de contar, aqui ver nada podr&aacute;s"));
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
/** 
 * 
 * Para cualquier duda o pregunta por favor ve a los foros del SDK: 
 *                www.zenphp.es
 * 
 * ============================================================================== 
 * 
 * @version $Id: clase_zen_cronometro.php,v 1 2007/09/25 $ 
 * @copyright Copyright (c) 2007 Juan Belon
 * @author Juan Belon
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License (GPL) 
 * 
 * ============================================================================== 
 * Ejemplo de uso: 
 * $t_carga = new zen_cronometro(true); 
 * sleep(4);
 * echo $t_carga->obtenerTiempo(); 
 */     
  class zen_cronometro { 
 	/**
 	 * Guarda el timestamp inicial desde el que empezar a contar...
 	 *
 	 * @var double
 	 */
 	var $inicio;
 	/**
 	 * Guarda el timestamp final para hacer la resta con el $inicio y obtener el total
 	 *
 	 * @var double
 	 */
	var $fin; 
	/**
	* Constructor de la clase, si $empezar_ahora es true comienza a contar con la funcion empezar()
	*
	* @param bool $empezar_ahora
	* @return zen_cronometro
	*/
	function zen_cronometro($empezar_ahora=false){
	   if ($empezar_ahora) $this->empezar();
	} 
	/**
	 * Comienza a contar el tiempo...
	 *
	 */
	function empezar(){ 
	  $this->inicio = $this->obtenerTiempo();
	} 
	/**
	 * Termina de contar
	 *
	 */
	function terminar(){ 
	   $this->fin = $this->obtenerTiempo(); 
	} 
	/**
	 * Devuelve la cuenta total, es decir la resta del final - principio, si el final no esta definido,se termina la cuenta y se guarda para realizar el calculo
	 *
	 * @param str $formato
	 * @return str
	 */
	function cuentaTotal($formato = '%01.2f'){ 
	   if (empty($this->fin) )$this->terminar(); 
	   return sprintf($formato, ($this->fin - $this->inicio)); 
	} 
	/**
	 * Devuelve el timestamp actual
	 *
	 * @return double
	 */
	function obtenerTiempo(){ 
	 	$tiempo = microtime(); 
		$tiempo = explode(' ', $tiempo); 
		return doubleval( $tiempo[1] + $tiempo[0] ) ;
	} 
 } 
 
 
?>