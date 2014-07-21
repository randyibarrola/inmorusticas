<?php
/**
 * clase_zen_error_log.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Una clase PHP simple para guardar los logs de errores de zenphp
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined('ZF_SEGURO_ZEN')) die(_("Error ,horrror: no puedes acceder aqui primor ;-)"));
class zen_error_log {
    ##################################################################
    ##        EJEMPLO DE USO                                        ##
    ##        $zLog = new zen_error_log();                         ##
    ##        $zLog->tipo      = "mysql";//httpd,mysql,zenphp       ##
    ##        $zLog->fichero   = "mysql_err/err.log";               ##
    ##        $zLog->contenido = $zLog->leerLogDeFichero();         ##
    ##                                                              ##
    ##                                                              ##
    ##################################################################    
    /**
     * Nombre del fichero donde guardar el log de errores
     *
     * @var str
     */
    var $fichero;
    /**
     * Contenido de todas las líneas de error
     *
     * @var str
     */
    var $contenido;
    /**
     * Tipo de log
     *
     * @var str
     */
    var $tipo;
    /**
     * Tamaño del fichero
     *
     * @var int
     */
    var $tamanio;
    /**
     * @var str
     */
    var $ruta;
    /**
     * Constructor
     */
    function zen_error_log($fichero,$tipo="zenphp"){
    	if (!is_dir(ZF_DIR_ERROR_LOG)) //TODO: Cambiar die por control de excepcion
    	 die(printf(_("El directorio de logs %s no existe,cr&eacute;alo o desactiva la opci&oacute;n ZF_MODO_GUARDAR_LOG en zen.php"),ZF_DIR_ERROR_LOG));
    	if (!is_writable(ZF_DIR_ERROR_LOG)) 
    	 die(printf(_("No se puede escribir en el directorio de log %s"),ZF_DIR_ERROR_LOG));
    	$this->tipo = $tipo;
    	$this->contenido = "";
    	$this->fichero = $fichero;
    }
	/**
	 * Lee un fichero de log de un $tipo dado y lo guarda en $this->contenido
	 *
	 * @param str $tipo
	 */
    function leerLogDeFichero($tipo="") {
    	if (!empty($tipo) || $tipo!=$this->tipo) $this->tipo = $tipo;
    	switch ($this->tipo){
    	 case "httpd" :
            $this->contenido = $this->leerLogDeFicheroApache();
        	break;
    	 case "mysql":
            $this->contenido = $this->leerLogDeFicheroMySQL();
            break;
         default: //zenphp
         	//El contenido ya está dentro :)
         	break;
        }
    }
	/**
	 * Intenta abrir el fichero de la ruta del log de errores y devuelve el manejador
	 *
	 * @param str $f
	 * @param str $modo
	 * @return int
	 */
    function abrirFichero($f="",$modo='rwb') {
    	if (!empty($f) || $f!=$this->fichero) $this->fichero = $f;
    	
    	if (!file_exists(ZF_DIR_ERROR_LOG.$this->fichero)){
    		//TODO: cambiar die por excepción + return false
    		die(printf(_("El fichero %s no existe"),ZF_DIR_ERROR_LOG.$this->fichero));
    	 	//return false;
    	}
			
		if ( ! $fp = @fopen($this->ruta.DIRECTORY_SEPARATOR.$f,"a"))
		{
			//TODO: cambiar die por excepción + return false
    		die(printf(_("El fichero %s no se puede escribir"),ZF_DIR_ERROR_LOG.$this->fichero));
			//return FALSE;
		}
		return $fp; //Devolvemos el manejador del fichero
    }
    /**
     * Escribe el mensaje pasado en el fichero y devuelve el resultado
     *
     * @param str $mensaje
     * @return bool
     */
    function escribirMensaje($mensaje){
    	$fp = $this->abrirFichero();
    	if (!$fp) return false;
		//Ahora bloqueamos el fichero para escribir el log:
		flock($fp, LOCK_EX);
		fwrite($fp,date("[d-m-Y]")." ::"); //fecha y hora del error
		fwrite($fp, $mensaje); //mensaje de error a escribir
		fwrite($fp, "\n");
		flock($fp, LOCK_UN); //Desbloqueo del fichero
    	close($fp); //Cerramos el fichero y devolvemos todo ok
    	return true;
    }
    /**
     * Lee el log de tipo de MySQL y devuelve un array con todos ellos
     *
     * @return array
     */
    function leerLogDeFicheroMySQL() {
    	$tama = filesize($this->fichero);
        $mane = $this->abrirFichero($this->fichero,"rb"); //manejador del fichero
        if (!$mane) return false;
        $contenidos = fread($mane, $tama); //Leer los datos del fichero
		//Construimos el array de lineas con el contenido leido del fichero:
        $lineas = explode("\n",$contenidos);
        $result = array();
		$aux    = false; //Para usar la expresión regular
		//TODO:PROCESAMIENTO DE LOS DATOS DEL LOG DE MYSQL
		foreach ($lineas as $linea) {
			preg_match("[\[+[a-zA-Z0-9._/ ]+\]]", substr($linea,15,strlen($linea)), $aux, PREG_OFFSET_CAPTURE);
			$result[] = $aux;
        }
        
        fclose($mane);
        return $result;
    }
    /**
     * Lee un fichero de log de tipo Apache y devuelve un array con todos ellos
     *
     * @return array
     */
    function leerLogDeFicheroApache() {
        $tama = filesize($this->fichero);
        $mane = fopen($this->fichero, "rb");
        $contenidos= fread($mane, $tama);

        $lineas = explode("\n",$contenidos);
        $result = array();

        foreach ($lineas as $linea) {
            if (strlen($lineas)!=strlen(eregi_replace("FATAL:","",$lineas))) {
                $result[] = array(_('Linea')=>$linea,_('Error')=>null,_('Tipo')=>_("[FATAL]"),_('Fecha')=>null);
            } else {
            	//TODO : PROCESAMIENTO DE LOS DATOS DEL LOG DE MYSQL
                $fecha ="";
                $error = substr($lineas,26,strlen($linea));
                preg_match("[\[+[a-zA-Z0-9._/ ]+\]]", $error, $fecha, PREG_OFFSET_CAPTURE);
                $result[] = array(_('Linea')=>$linea,_('Tama')=>substr($lineas,0,26),_('Error')=>$error,_('Fecha')=>$fecha[0][0]);

            }
        }
        fclose($mane);
        return $result;
    }
}
?>