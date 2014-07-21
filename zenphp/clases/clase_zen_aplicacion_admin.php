<?php
/**
 * clase_zen_aplicacion_admin.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase para generar aplicaciones de ADMINISTRADOR
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined('ZF_SEGURO_ZEN')) exit(_('&iquest;Qu&eacute; puedes administrar sin zenphp?.Acceso directo no permitido'));
/**
 * Nivel asociado con la clase de login y usuarios
 * @see clase_zen_login.php
 * @see clase_zen_sesiones_seguras.php
 * @var int 
 */
define('ZF_NIVEL_ADMIN',3);
zen___carga_clase('zen_html_admin');
/**
 * 
 *    Los inicializadores de la clase sirven para cargar clases y añadirlas a la clase de la aplicación como una variable en la instancia
 *     de esta y asi poder realizar las llamadas pertinentes,ya que si se está usando PHP4 no se puede hacer la llamada a la carga automática
 *  de clases pues no está añadida hasta la versión 5. Es una solución intermedia que permite el control de errores por parte del usuario.
 *  Se usan metiendo el nombre de la clase (de la clase en si no el fichero aunque si se quiere puede hacerse con nombre de fichero...) 
 *  separando las clases con una coma.
 */
class zen_aplicacion_admin extends zen {
	/**
	 * Bandera para saber si es administrador
	 *
	 * @var bool
	 */
	var $es_admin = false;
    /**
     * Constructor
     *
     * @param str $inicializadores
     * @param str $fichero_config
     * @return zen_aplicacion_admin
     */
    function zen_aplicacion_admin($inicializadores="",$fichero_config=""){
        parent::zen($inicializadores);
        $this->es_admin = isset($_SESSION['es_admin']) && $_SESSION['es_admin'] && isset($_SESSION['nivel']) && $_SESSION['nivel']>=ZF_NIVEL_ADMIN;
        //Mecanismo para cargar los datos de configuración de la base de datos
        if (empty($fichero_config)) {
         $ego = str_replace("_admin","",get_class($this)).".config";
        } else {
         $ego = str_replace(array("_admin",".php"),"",$fichero_config);
        }

        (zen___carga_aplicacion($ego)) or die( _("No se pudo cargar la configuraci&oacute;n de la aplicaci&oacute;n administradora") ); //Leer configuración
        //Intentamos conectarnos:
        $ego = str_replace(array(".","config"),"",$ego);
        if (defined($ego.'_usuario') && defined($ego.'_contrasena') &&  defined($ego.'_servidor') && defined($ego.'_bd')){
            if (!$this->bd->conectar(
                constant($ego.'_servidor'),constant($ego.'_usuario'),constant($ego.'_contrasena'),
                constant($ego.'_bd'),defined($ego.'_persistente')?constant($ego.'_persistente'):false,
                defined($ego.'_tipo')?constant($ego.'_tipo'):'mysql',
                defined($ego.'_puerto')?constant($ego.'_puerto'):false
                ))
            {
                /*echo constant($ego.'_servidor'),constant($ego.'_usuario'),constant($ego.'_contrasena'),
                constant($ego.'_servidor')," ",defined($ego.'_persistente')?constant($ego.'_persistente'):false," ",
                defined($ego.'_tipo')?constant($ego.'_tipo'):'mysql';*/
                trigger_error(_('No se pudo conectar a la base de datos,revise su configuraci&oacute;n!'));
            }
        } else die("\nNo hay BD");
        zen___carga_clase('zen_html_admin');
        $clase = "html_".$ego."_admin";
        if (class_exists($clase) || zen___carga_vista($clase,true)){
         $this->html =& new $clase($this);
        } else 
        $this->html =& new zen_html_admin($this);
    }
    /**
	 * Comprueba el login y redirige
	 *
	 */
	function login($usuario,$password){
		if (!is_object($this->login)) {
			zen___carga_clase('zen_login');
			$this->login =& new zen_login($this);
		}
		if (!is_callable(array($this->login,"hacer_login"))) 
		 trigger_error(sprintf(_("Necesito un m&eacute;todo para hacer login valido en la clase %s",get_class($this))));
		
		return $this->login->hacer_login($usuario,$password);
	}
}
?>