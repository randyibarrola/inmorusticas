<?php
/**
 * clase_zen_compactador.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase que contiene un compactador de cadenas, contiene un algoritmo para eliminar espacios que debería ser reemplazado por otro ,como minify : http://code.google.com/p/minify/ 
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
/**
 *
 * @abstract Esta clase puede ser usada para aumentar la velocidad en servir páginas a un cliente,compactando
 * espacios en blanco. Para ello se incluyen multitud de opciones, incluyendo espacios en vertical y horizontal
 * a eliminar así como la posibilidad de compactar HTML,CSS y JavaScript.
 * Se puede usar para compactar la salida de un script usando la salida del búfer de PHP automático.
 *
 * @example
 * <?php
 * 		// un ejemplo sencillo que compacta automáticamente cualquier búfer de salida del script
 * 		$compactador = new zen_compactador(array(
 * 			'usar_buffer'		 => true,
 * 			'mostrar_buffer'		 => true,
 * 			'compactar_en_destructor'=> true
 * 		));
 * ?>
 *
 * @todo Funcionalidad para añadir compresión gzip del módulo apache mod_gzip o mod_defalte sniffing.
 */
class zen_compactador
{
	/**
	 * Almacena la matriz de valores de las opciones
	 * @access private
	 * @exception No usar como textos de reemplazo cadenas con # ya que se usan en las plantillas (@see clase_zen_plantilla.php)
	 * @var array
	 */
	var $_opciones = array(
	// 			salto_linea; str; Tipo de salto de línea usado en la cadena procesada
	// 			ie, \r, \r\n, \n
	'salto_linea' 					=> "\n",
	// 			etiquetas_reservadas; array; Un array de etiquetas para las que sus contenidos innerHTML han de preservarse.
	'etiquetas_reservadas'				=> array('textarea', 'pre', 'script', 'style', 'code'),
	// 			zona_reservada; str; Bloque de texto usado para reemplazar los contenidos de las etiquetas reservadas
	// 			mientras se compacta el resto.
	'zona_reservada'					=> '@@ETIQUETARESERVADA@@',
	// 			usar_buffer; boolean; Opcional: Puedes usar el buffer de salida en su lugar para importar a mano la cadena a procesar.
	'usar_buffer' 					=> false,
	// 			mostrar_buffer; boolean; Si despues del procesamiento quieres mostrar el contenido, ponerlo a true, en otro caso
	// 			se usa para mostrar el resultado compactado.
	'mostrar_buffer'					=> true,
	// 			compactar_en_destructor; boolean; Si 'usar_buffer' es true y la opcion es true se crea una asociación del destructor:
	// 			register_shutdown_function que procesará la salida del buffer al salir del script sin problema.
	'compactar_en_destructor'			=> false,
	// 			quitar_comentarios; boolean; Quita los comentarios de la cadena original. Si 'mantener_comentarios_condiciones'
	// 			no es true entonces los comentarios condicionales tipo Internet Explorer también se quitan.
	'quitar_comentarios' 				=> true,
	// 			mantener_comentarios_condiciones; boolean; Sólo se aplica si 'quitar_comentarios' es true.
	// 			Sólo si el cliente navegador es Internet Explorer se mantienen los comentarios. [por reglas JS,CSS,DHTML]
	'mantener_comentarios_condiciones'	=> true,
	// 			limites_condiciones; array; Los limites de las condiciones son usados para reemplazar las etiquetas de apertura
	// 			y cierre de los comentarios condicionales tipo Internet Explorer.
	'limites_condiciones'				=> array('@@IECOND-ABRE@@', '@@IECOND-CIERRA@@'),
	// 			comprimir_horizontal; boolean; Elimina en horizontal los espacios en blanco, i.e. de izquierda a derecha (espacios y tabulaciones).
	'comprimir_horizontal'				=> true,
	// 			comprimir_vertical; boolean; Elimina en vertical los espacios en blanco de la cadena original, i.e. saltos de línea.
	'comprimir_vertical'				=> true,
	// 			comprimir_scripts; boolean; Comprime el contenido de etiquetas script usando un algoritmo. Elimina los comentarios en JS,
	// 			asi como los espacios en blanco horizontales y verticales. Algoritmo como 'minify' de http://code.google.com/p/minify/ or 'jsmin'
	// 			http://code.google.com/p/jsmin-php/
	'comprimir_scripts'				=> true,
	// 			funcion_compresion_script; boolean; Nombre de la llamada a una función de compresión js.
	'funcion_compresion_script' 		=> false,
	// 			funcion_compresion_script_args; array; Cualquier argumento adicional de la llamada. El javascript será puesto
	// 			al principio del array.
	'funcion_compresion_script_args' 	=> array(),
	// 			comprimir_css; boolean; Comprimir etiquetas de estilo en lenguaje CSS.
	'comprimir_css'					=> true
	);

   /**
	* Contiene una bandera de si se procesaron o no los bloques reservados para que no se tengan que hacer operaciones extra al analizar.
	* @access private
	* @var mixed 
 	*/
	var $_bloques_reservados  = false;

	/**
	 * Constructor
	 */
	function zen_compactador($opciones=array())
	{
		$this->ponerOpcion($opciones);
		if($this->_opciones['compactar_en_destructor'])
		{
			$this->ponerOpcion(array(
			'usar_buffer' => true,
			'mostrar_buffer' => true
			));
		}
		if($this->_opciones['usar_buffer'])	{
			//Terminar cualquier buffer de salida anterior y comenzar el nuevo:
			ob_end_clean(); ob_end_flush();
			ob_start();
		}
		if($this->_opciones['compactar_en_destructor'])	{
			//Registrar el destructor de la clase:
			register_shutdown_function(array(&$this, 'destructor'));
		}
	}

	/**
	 * Establece una opción del array de opciones interno
	 * 
	 * @access public
	 * @param mixed $variable
	 * @param mixed $valor
	 **/
	function ponerOpcion($variable, $valor=null)
	{
		$llaves = array_keys($this->_opciones);
		if(gettype($variable) == 'array') //es un array_merge casero
		{
			foreach($variable as $nombre=>$value)
			{
				if(in_array($nombre, $llaves))
				{
					$this->_opciones[$nombre] = $value;
				}
			}
		}
		else
		{
			if(in_array($variable, $llaves))
			{
				$this->_opciones[$variable] = $valor;
			}
		}
	}

	/**
	 * Comprime la cadena fuente (HTML), se haya pasado a la función o se esté usando un búfer:si usar_buffer
	 * es true-> buffer se coge para la compresión/compactación.
	 * 
	 * @access public
	 * @param str $cadena HTML str para compactar/comprimir, aunque si usar_bufferes true
	 * 	entonces el parametro es olvidado.
	 * @return str
	 */
	function destructor($cadena=null)
	{
		if($this->_opciones['usar_buffer'])
		{
			$cadena = ob_get_clean();
		}
		// 			unificar los saltos de linea
		$cadena = $this->_unificar_saltos_linea($cadena);
		// 			comprimir cualquier etiqueta script si es necesario
		if($this->_opciones['comprimir_scripts'] || $this->_opciones['comprimir_css'])
		{
			$cadena = $this->_comprimirScripts_y_Estilo($cadena);
		}
		// 			realizar compresiones
		if($this->_opciones['quitar_comentarios'])
		{
			$cadena = $this->_quitarComentariosHTML($cadena);
		}
		if($this->_opciones['comprimir_horizontal'])
		{
			$cadena = $this->_comprimir_en_horizontal($cadena);
		}
		if($this->_opciones['comprimir_vertical'])
		{
			$cadena = $this->_comprimir_en_vertical($cadena);
		}
		// 			reemplazar los bloques reservados con su contenido original:
		$cadena = $this->_recuperarBloquesReservados($cadena);
		// 			si la cadena html ha de mostrarse, se muestra XD
		if($this->_opciones['mostrar_buffer'])
		{
			echo $cadena;
		}
		return $cadena;
	}

	/**
	 * Quita los comentarios HTML del buffer, comprueba si hay alguno de tipo
	 * Inernet Explorer conditional para quitarlo o no.
	 *
	 * @access private
	 * @param str $cadena
	 * @return str
	 */
	function _quitarComentariosHTML($cadena)
	{
		$mantener_condicionales = false;
		// 			sólo se procesa si queremos mantener los de tipo Internet Explorer
		if($this->_opciones['mantener_comentarios_condiciones'])
		{
			// 				comprueba que el navegador es Internet Explorer
			$msie = '/msie\s(.*).*(win)/i';
			$mantener_condicionales = (isset($_SERVER['HTTP_USER_AGENT']) && preg_match($msie, $_SERVER['HTTP_USER_AGENT']));
			// 			    $mantener_tipo_doc = false;
			// 			    if(strpos($cadena, '<!DOCTYPE'))
			// 			    {
			// 					$cadena = str_replace('<!DOCTYPE', '--**@@DOCTYPE@@**--', $cadena);
			// 			   	 	$mantener_tipo_doc = true;
			// 			    }
			// 			    condicionales ie para mantener o substituir:
			if($mantener_condicionales)
			{
				$cadena = str_replace(array('<!--[if', '<![endif]-->'), $this->_opciones['limites_condiciones'], $cadena);
			}
		}
		// 		    quitar comentarios
		$cadena = preg_replace('/<!--(.|\s)*?-->/', '', $cadena);
		// 		    $cadena = preg_replace ('@<![\s\S]*?--[ \t\n\r]*>@', '', $cadena);
		// 		   	condiciones adicionales en subcadenas
		if($mantener_condicionales)
		{
			$cadena = str_replace($this->_opciones['limites_condiciones'], array('<!--[if', '<![endif]-->'), $cadena);
		}
		// 		    if($mantener_tipo_doc)
		// 		    {
		// 				$cadena = str_replace('--**@@DOCTYPE@@**--', '<!DOCTYPE', $cadena);
		// 		    }
		// 			devolver el buffer
		return $cadena;
	}

	/**
	 * Encontrar los bloques de HTML reservados,para no formatearlos
	 * 
	 * @access private
	 * @param str $cadena
	 * @return str
	 */
	function _extraerBloquesReservados($cadena)
	{
		if($this->_bloques_reservados !== false)
		{
			return $cadena;
		}
		$etiquetas = implode('|', $this->_opciones['etiquetas_reservadas']);
		// 			obtener areas de texto
		preg_match_all("!<(".$etiquetas.")[^>]*>.*?</(".$etiquetas.")>!is", $cadena, $preserved_area_match);
		$this->_bloques_reservados = $preserved_area_match[0];
		// 			reemplazar areas de texto marcados
		return preg_replace("!<(".$etiquetas.")[^>]*>.*?</(".$etiquetas.")>!is", $this->_opciones['zona_reservada'], $cadena);
	}

	/**
	 * Reemplaza cualquier marca hecha con el contenido original
	 * 
	 * @access private
	 * @param str $cadena
	 * @return str
	 */
	function _recuperarBloquesReservados($cadena)
	{
		if($this->_bloques_reservados === false)
		{
			return $cadena;
		}
		foreach($this->_bloques_reservados as $bloque_actual)
		{
			$cadena = preg_replace("!".$this->_opciones['zona_reservada']."!", $bloque_actual, $cadena, 1);
		}
		return $cadena;
	}

	/**
	 * Comprime los espacios en horizontal(espacios, tabs,etc) manteniendo eso si
	 * areas de texto y otros como pre
	 * La idea viene de la clase de plantillas:
	 * http://smarty.php.net/contribs/plugins/view.php/outputfilter.trimwhitespace.php
	 *
	 * @access private
	 * @param str $cadena
	 * @return str
	 */
	function _comprimir_en_horizontal($cadena)
	{
		$cadena = $this->_extraerBloquesReservados($cadena);
		// 			eliminar espacios
		$cadena = preg_replace('/((?<!\?>)'.$this->_opciones['salto_linea'].')[\s]+/m', '\1', $cadena);
		// 			eliminar espacios extra
		return preg_replace('/\t+/', '', $cadena);
	}

	/**
	 * Comprime los espacios en vertical(espacios, tabs,etc) manteniendo eso si
	 * areas de texto y otros como pre
	 *
	 * @access private
	 * @param str $cadena
	 * @return str
	 */
	function _comprimir_en_vertical($cadena)
	{
		$cadena = $this->_extraerBloquesReservados($cadena);
		// 			eliminar espacios
		return str_replace($this->_opciones['salto_linea'], '', $cadena);
	}

	/**
	 * Convierte saltos de linea de diferentes plataformas en un sólo tipo
	 *
	 * @access private
	 * @param str $cadena HTML str
	 * @param str $break Formato del salto de linea a unificar: "\r\n" o "\n"
	 * @return str
	 */
	function _unificar_saltos_linea($cadena)
	{
		return preg_replace ("/\015\012|\015|\012/", $this->_opciones['salto_linea'], $cadena);
	}

	/**
	 * Comprime espacios en blanco en vertical (saltos de línea) preservando
	 * areas de texto y contenido en <pre>. Esto usa el método '_comprimeCodigoSimple' para
	 * comprimir el javascript, de todas formas, es posible usar otra librería más útil como
	 * 'minify' http://code.google.com/p/minify/ ya que esta función tiene sus limitaciones
	 * con comentarios y otras expresiones regulares. Puedes usar tu propia función con la 
	 * opción 'llamada_compresion_js'.
	 *
	 * @access private
	 * @param str $cadena
	 * @return str
	 */
	function _comprimirScripts_y_Estilo($cadena)
	{
		$comprimir_scripts = $this->_opciones['comprimir_scripts'];
		$comprimir_css = $this->_opciones['comprimir_css'];
		$usar_llamada_script = $this->_opciones['funcion_compresion_script'] != false;
		// 			Encontrar todas las etiquetas script con pregmatch
		$scripts = preg_match_all("!(<(style|script)[^>]*>(?:\\s*<\\!--)?)(.*?)((?://-->\\s*)?</(style|script)>)!is", $cadena, $partes_script);
		// 			y guardar las partes encontradas
		$comprimido = array();
		$partes     = array();
		$n          = count($partes_script[0]);
		for($i=0; $i<$n; $i++)
		{
			$codigo = trim($partes_script[3][$i]);
			$no_vacio = !empty($codigo);
			$es_script = ($comprimir_scripts && $partes_script[2][$i] == 'script');
			if($no_vacio && ($es_script || ($comprimir_css && $partes_script[2][$i] == 'style')))
			{
				if($es_script && $usar_llamada_script)
				{
					$argumentos_llamada = $this->_opciones['funcion_compresion_script_args'];
					if(gettype($argumentos_llamada) !== 'array')
					{
						$argumentos_llamada = array($argumentos_llamada);
					}
					array_unshift($argumentos_llamada, $codigo);
					$mi_llamada = call_user_func_array($this->_opciones['funcion_compresion_script'], $argumentos_llamada);
				}
				else
				{
					$mi_llamada = $this->_comprimeCodigoSimple($codigo);
				}
				array_push($partes, $partes_script[0][$i]);
				array_push($comprimido, trim($partes_script[1][$i]).$mi_llamada.trim($partes_script[4][$i]));
			}
		}
		// 			hacer los reemplazos y devolver el resultado:
		return str_replace($partes, $comprimido, $cadena);
	}

	/**
	 * Usa preg_replace para comprimir el código : espacios en blanco de ie, javascript y css
	 * Es recomendable usar otra libreria como http://code.google.com/p/minify/
	 *
	 * @access private
	 * @param str $codigo Cadena de Codigo
	 * @return str
	 **/
	function _comprimeCodigoSimple($codigo)
	{
		// 			Eliminar comentarios multilinea:
		$codigo = preg_replace('/\/\*(?!-)[\x00-\xff]*?\*\//', '', $codigo);
		// 			Eliminar comentarios de una linea:
		// 			$codigo = preg_replace('/[^:]\/\/.*/', '', $codigo);
		$codigo = preg_replace('/\\/\\/[^\\n\\r]*[\\n\\r]/', '', $codigo);
		$codigo = preg_replace('/\\/\\*[^*]*\\*+([^\\/][^*]*\\*+)*\\//', '', $codigo);
		// 			Eliminar espacios extra:
		$codigo = preg_replace('/\s+/', ' ', $codigo);
		// 			Eliminar espacios que pueden ser borrados:
		return preg_replace('/\s?([\{\};\=\(\)\/\+\*-])\s?/', "\\1", $codigo);
	}
}
?>