<?php
/**
 * clase_zen_login.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic La magia está en la construcción de los métodos ayudantes para usar el login automático
 * @see config/zen_login.php
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
class zen_login {
	/**
	 * Puntero a la clase de base de datos del padre
	 *
	 * @var zen_basedatos
	 */
	var $bd;
	/**
	 * Clase web padre que crea a esta
	 *
	 * @var zen
	 */
	var $padre;
	/**
	 * constructor
	 * @param zen $padre
	 * @return zen_login
	 */
	function zen_login(&$padre){
		$this->padre =& $padre;
		$this->bd	 =& $padre->bd;		
		//Cargar configuracion del login:
		//$this->padre->config->cargar_configuracion_fichero_ini('zen_login.ini');
		require_once(ZF_DIR_CONFIG.'zen_login.php');
		//Constantes de idioma para el login
		/**
		 * 
		 * TODO: Cargar constantes de idiomas de un fichero .PHP o .INI
		 $this->padre->config->cargar_constantes_idioma_ini(
			
		);*/
	}
	/**
	 * Comprueba la sesion actual
	 *
	 * @return bool
	 */
	function existeSesion(){
		if ($this->padre->sesiones->comprobar_sesion()) {
			$idu = $this->bd->seleccion_unica(ZF_LOGIN_CAMPO_ID_USUARIO." from usuarios where ".ZF_LOGIN_CAMPO_ID_USUARIO."=".intval($_SESSION['idu']));
			return ($idu>0); //existe?
		} else return false;
	}
	/**
	 * Comprueba el login devolviendo el identificador de la tabla de usuarios (personas)
	 *
	 * @param str $usuario
	 * @param str $password
	 * @return int
	 */
	function hacer_login($usuario,$password){
		$idu = $this->bd->seleccion_unica(
			ZF_LOGIN_CAMPO_ID_USUARIO." from ".ZF_LOGIN_TABLA." where ".
			ZF_LOGIN_CAMPO_USUARIO."='".zen_sanar($usuario)."' and ".
			ZF_LOGIN_CAMPO_CONTRASENA."=md5('".zen_sanar($password)."')"
			);
 		if (!$idu) { 
 			return false;
 		} else {
 			$this->crearSesion($idu,ZF_MODO_DEPURACION);
 		 	return true;	
 		}
	}
	/**
	 * Hace el logout...
	 *
	 */
	function logout(){
		if ($this->existeSesion())
			$this->padre->sesiones->destruir_sesion();
	}
	/**
	 * Crea la sesion en cuestion a usar
	 *
	 * @param int $idUsuario
	 * @param bool $debug
	 */
	function crearSesion($idUsuario,$debug=false){
		$this->padre->sesiones->iniciar();
		$_SESSION['idu']      = $idUsuario;
		$_SESSION['usuario']  = $this->bd->seleccion_unica(ZF_LOGIN_CAMPO_CORREO." from ".ZF_LOGIN_TABLA.
								" where ".ZF_LOGIN_CAMPO_ID_USUARIO."=".$idUsuario);
		if (defined('ZF_LOGIN_CAMPO_NIVEL'))
		 $_SESSION['nivel']    = $this->bd->seleccion_unica(ZF_LOGIN_CAMPO_NIVEL." from ".ZF_LOGIN_TABLA.
		 						" where ".ZF_LOGIN_CAMPO_ID_USUARIO."=".$idUsuario);
		//En el caso en que haya una lista de grupos ,se utiliza fácilmente:
		if (defined('ZF_LOGIN_CAMPO_LISTA_IDGRUPOS')){
		 $_SESSION['idgrupos'] = $this->bd->seleccion_unica(ZF_LOGIN_CAMPO_LISTA_IDGRUPOS." from ".ZF_LOGIN_TABLA.
		 						" where ".ZF_LOGIN_CAMPO_ID_USUARIO."=".$idUsuario);
		 //Para obtener la lista de todos los grupos se puede hacer algo como lo siguiente:
		 /////////////////////////////////////////////////////////////////////////////////
		 /*//Quitar comentarios para utilizar grupos de usuarios:
		 $_SESSION['grupos']   = "";
		 for ($i=0; $i<sizeof($_SESSION['idgrupos']); $i++){
		 	$_SESSION['grupos'] .= $this->bd->seleccion_unica(ZF_LOGIN_CAMPO_NOMBRE_GRUPO.
		 			" from ".ZF_LOGIN_TABLA_GRUPOS." where idgrupos=".intval($_SESSION['igrupos'][$i]));
		 	if ($i<count($_SESSION['idgrupos'])) $_SESSION['grupos'].=',';
		 }
		 */
		 /////////////////////////////////////////////////////////////////////////////////
		 //por ultimo dejamos en formato legible la variable de sesion de grupos, i.e.,como un array:
		 //pasandola de cadena a array:
		 $_SESSION['idgrupos'] = zen_deserializar($_SESSION['idgrupos']);
		}
		if ($_SESSION['nivel']>2) $_SESSION['es_admin'] = 1;
/*		if ($debug) {
	 	 	return ($_COOKIE);
		 echo '<br>SESION:';
			($_SESSION);
		}
*/
	}

}
?>