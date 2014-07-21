<?php
/**
 * clase_zen_sesiones_seguras.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase para manipular sesiones en PHP pero de forma segura ;)
 * @see zen_login
 */
/* +----------------------------------------------------------------------
 |                  _       
 |  ___ ___ ___ ___| |_ ___ 
 | |- _| -_|   | . |   | . |
 | |___|___|_|_|  _|_|_|  _|
 |             |_|     |_|  
 | 
 | 
 | zenphp.es 
 +----------------------------------------------------------------------*/
if (!defined('ZF_SEGURO_ZEN')) die(_('Acceso directo no permitido'));
class zen_sesiones_seguras
{
	/**
	 * Incluir el navegador?
	 *
	 * @var bool
	 */
	var $comprueba_navegador = true;
	/**
	 * Numeros a usar de la Ip para la firma segura
	 *
	 * @var int
	 */
	var $num_bloques_ip = 0;
	/**
	 * Palabra secreta
	 *
	 * @var str
	 */
	var $palabra_secreta = 'SECRETO';
	/**
	 * Regenerar ID para evitar que se copien valores de $_SESSION?
	 *
	 * @var bool
	 */
	var $regenerar_id = true;
	/**
  	 * Clase base del sistema
  	 *
  	 * @var zen
  	 */
	var $padre;
	/**
	 * Constructor
	 *
	 * @param clase_zen $_padre
	 * @return sesiones_seguras
	 */
	function zen_sesiones_seguras(&$_padre){
		$this->padre = &$_padre;
	}
	/**
	 * Crea la sesion
	 *
	 */
	function iniciar()
	{
		$_SESSION['ss_firma'] = $this->_firmarSesion();
		$this->_regenerarId();;
	}

	/**
	 * Funcion para comprobar que la sesion es correcta
	 *
	 * @return bool
	 */
	function comprobar_sesion()
	{
		$this->_regenerarId();
		return (isset($_SESSION['ss_firma'])
		 && $_SESSION['ss_firma'] == $this->_firmarSesion());
	}

	/**
	 * Funcion interna para firmar y devolver el MD5 de la sesion segura
	 *
	 * @return int
	 */
	function _firmarSesion()
	{
		$firma = $this->palabra_secreta;
		if ($this->comprueba_navegador)
		{
			$firma .= $_SERVER['HTTP_USER_AGENT'];
		}
		if ($this->num_bloques_ip)
		{
			$num_bloques = abs(intval($this->num_bloques_ip));
			if ($num_bloques > 4)
			{
				$num_bloques = 4;
			}
			$bloques = explode('.', $_SERVER['REMOTE_ADDR']);
			for ($i=0; $i<$num_bloques; $i++)
			{
				$firma .= $bloques[$i] . '.';
			}
		}
		return md5($firma);
	}

	/**
	 * Funcion interna para regenerar el ID de sesion si es posible claro :P
	 *
	 */
	function _regenerarId()
	{
		if ($this->regenerar_id && function_exists('session_regenerate_id'))
		{
			session_regenerate_id();
		}
	}

	/**
	 * Llama a session_destroy()
	 *
	 */
	function destruir_sesion(){
		@session_destroy();
	}
}
?>