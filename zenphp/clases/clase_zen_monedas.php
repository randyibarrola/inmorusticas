<?php
/**
 * clase_zen_monedas.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Devuelve información recogida del Banco Central Europeo.
 * @abstract La clase devuelve la información como un array con dos elementoos: fecha y valor.
 * @internal Con PHP4 usa DOM XML y con PHP5 usa la clase DOM
 * @see domxml, zenphp/contenido/plantillas/es/monedas.xml
 * @example
 * <?php
 * zen___carga_clase('zen_monedas');
 * $z = new zen_monedas();
 * print_r($z->obtenerDatos());
 * ?>
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
if (!defined('ZF_SEGURO_ZEN')) exit('La moneda de cambio es que no puedes acceder aqu&iacute; directamente sin zenphp');
class zen_monedas {
	/**
	 * Servidor de moneda utilizado
	 *
	 * @var str
	 */
	var $origen = "http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml";
	/**
	 * Cadena donde se almacena el fichero XML leido
	 *
	 * @var str
	 */
	var $cadena_xml;
	/**
     * Constructor de la clase, si se usa la URL se toma desde la direccion el fichero, si se establece un fichero se ignora la url
     *
     * @param str $usar_url Direccion URL completae del fichero XML en el formato del Banco Central Europeo
     * @param str $usar_fichero ruta completa del fichero XML en el formato del Banco Central Europeo
     * @return zen_moneda
     */
	function zen_monedas($usar_url="",$usar_fichero=""){
		if (!empty($usar_fichero)){
			if (file_exists($usar_fichero))
			 $this->origen = $usar_fichero;
			else
			 trigger_error(_("zen_moneda Error: No se puede encontrar el fichero ").$usar_fichero,E_USER_WARNING);
		} else {
			if (!empty($usar_url))
			 $this->origen = $usar_url;
			else{
			 $this->origen = ZF_DIR_CONTENIDO_ZEN.'plantillas'.DIRECTORY_SEPARATOR.'es'.DIRECTORY_SEPARATOR.'monedas.xml';
			 
			}
		}
		if (is_readable($this->origen))
		 @$this->cadena_xml = file_get_contents($this->origen);
		if (empty($this->cadena_xml)) 
			trigger_error(_("zen_moneda Error: No se pudo leer el origen de datos: ").
				$this->origen._(" o est&aacute; vacio"),E_USER_WARNING);
	}
	/**
	 * Devuelve el array de los datos leidos
	 *
	 * @return array
	 */
	function obtenerDatos(){
		if(function_exists('domxml_open_mem')){
			return $this->usarDOMXML();
		}/*else{
			return $this->usarDOM();
		}*/
	}
	/**
	 * Devuelve el array de datos usando DOM XML
	 *
	 * @return array
	 */
	function usarDOMXML(){
		$xml=domxml_open_mem($this->cadena_xml);
		$raiz = $xml->document_element();
		$nodo=$raiz->get_elements_by_tagname('Cube');
		$monedas = array();
		$monedas['fecha']=$nodo[1]->get_attribute('time');
		$hijos=$nodo[1]->get_elements_by_tagname('Cube');
		foreach($hijos as $hijo){
			$monedas['monedas'][$hijo->get_attribute('currency')] = floatval($hijo->get_attribute('rate'));
		}
		
		return $monedas;
	}
	/**
	 * Devuelve el array de datos usando un objeto DOM Document
	 *
	 * @return array
	 *
	function usarDOM(){
		$xml=new DOMDocument();
		$xml->loadXML($this->servidor_moneda);
		$nodo=$xml->getelementsByTagName('Cube');
		eval("{$monedas['fecha']} = $nodo->item(1)->getAttribute('time');"); //para evitar problemas con PHP4
		eval("$hijos = $nodo->item(1)->getelementsByTagName('Cube')");
		foreach($hijos as $item){
			$k=strtolower($item->getAttribute('currency'));
			$monedas['monedas'][$k]=($item->hasAttribute('multiplier')?$item->getAttribute('rate')/$item->getAttribute('multiplier'):$item->getAttribute('rate'));
		}
		return $monedas;
	}*/
}
?>