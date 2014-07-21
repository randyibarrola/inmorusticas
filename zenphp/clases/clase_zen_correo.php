<?php
/**
 * clase_zen.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase que contiene el sistema del que cuelgan las demás clases y aplicaciones...
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
//Carga la clase phpmailer del directorio de Librerías...
//TODO: Cambiar die por una excepción
if ( !zen___carga_clase('phpmailer') ) die(_('Error cargando clase phpmailer en la clase correo'));
class zen_correo extends phpmailer {
	/**
	 * Contenedora de todas las clases de la web
	 *
	 * @var zen
	 */
	var $padre;
	/**
	 * Clase para operar con la base de datos MySQL
	 *
	 * @var zen_basedatos
	 */
	var $bd;

	/**
	 * Direccion para poner en los correos
	 *
	 * @var str
	 */
	var $url = SITIO_WEB;
	/**
	 * Constructor de la clase de correos
	 *
	 * @param zen $padre
	 * @return zen_correo
	 */
	function zen_correo(&$padre) {
		$this->PluginDir = ZF_DIR_LIBRERIAS;
		$this->padre = &$padre;
		$this->bd    = &$padre->bd;
		//Parametros del correo: evitar ser marcados como spammers usando smtp
		//$this->Mailer   = "smtp";
		$this->Host     = "localhost";
		$this->SMTPAuth = false;
		//Si tenemos que autentificarnos:
		/*
		$this->Username = "usuario";
		$this->Password = "password";
		*/
		$this->From     = ZF_CORREO_ADMIN;
		$this->FromName = ZF_NOMBRE_SITIO;
		$this->Timeout  = 120;
		$this->CharSet  = "utf-8";
		$this->IsHTML(true);
		//fin -parametros del correo
	}

	/**
	 * Envia un correo rellenandolo con una plantilla y la informacion pasada a los destinarios. 
         * Devuelve el resultado del envio.
         * Si el fichero no existe se intentará abrir la $url y construir el cuerpo con el contenido que tenga
         * Como el correo se manda como HTML se añade un parámetro para insertar un cuerpo alternativo que puede ser
         * el mismo que $cuerpo pero con el filtro zen_borraHTML($cuerpo) por ejemplo
	 *
	 * @param str $nombre
	 * @param str $subject
	 * @param str $cuerpo
         * @param str $url
	 * @param array $destinos
         * @param str $cuerpo_alternativo
	 * @return bool
	 */
	function enviarCorreo($nombre="webmaster",$subject="Tema del correo",$cuerpo="fichero.html",$url="http://www...",&$destinos,$cuerpo_alternativo=""){
		$limite = 1;
		$this->Subject = $subject;
                $this->AltBody = empty($cuerpo_alernativo)?zen_borraHTML($cuerpo):zen_borraHTML($cuerpo_alternativo);
		$this->Body = $this->construyeCuerpo(0,$cuerpo,$url);
		$resultado = true;
		for ($j=0,$i=1,$k=1; $j<sizeof($destinos); $j++,$i++) {
			$this->AddBCC($destinos[$j],$destinos[$j]);
			if ($i==$limite){
				//info de depuracion, quitar comentario # para activarlo y saber como funcionan los envios
				#echo "($i) enviando el correo num $k<br>";
				$k++;
				#print_r($this->bcc);
				$resultado = $this->Send();
				$this->ClearBCCs();
				$i = 0;
			}
		}
		if ( (sizeof($destinos)<$limite) || (intval(sizeof($destinos)/$limite)!=$k) ){
			#echo "Enviando el ultimo ";
			#print_r($this->bcc);
			$this->Send();
			return true;
		} 
		return true; //El $resultado no es real porque el servidor puede devolver alertas y queda como false,cuando todo ha ido bien...
	}

	
	/**
	 * Rellena las variables de la clase con el HTML del correo a enviar
	 *
	 * @param str $tipo
	 * @param str $fichero
	 * @return str : html
	 */
	function construyeCuerpo($tipo=0,$fichero="",$url=""){
		switch ($tipo) {
			case 0 : default: //FICHERO
				//$p = new clase_plantilla($this->padre->padre); //&$this->padre->HTML-> plantilla;
				if (!file_exists($fichero)) {
                                  return file_get_contents($url);
                                }
				else return file_get_contents($fichero);				
				break;
		} //switch
	}

}
?>