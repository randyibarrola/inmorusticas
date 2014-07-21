<?php
/**
 * clase_zen_xcs.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase que ayuda a analizar y procesar XCS : CSS con contenido PHP que permiten CSS dinámicos :)
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
/**
 * Zen_Xcs es una clase para analizar sintácticamente asi como comprimir y realizar
 * otras operaciones con XCS/CSS
 * XCS viene de "extened Cascading Stylesheet".
 * "extenido", porque XCS introduce bastantes nuevos conceptos
 *     1) Variables globales AL PROCESO (ejemplo. '!colorFondo = #333;');
 *     2) Operaciones aritméticas simples con variables y/o
 *        variantes con enteros ("simple" quiere decir de solo dos operandos).
 *        (ej. '!ancho = !anchoColumna * !numeroColumnas;')
 *        (ej. '!ancho = !anchoColumna * 4;')
 *     3) Maneja expresiones complejas si las hay y esta permitido:
 *        (ej. '!fecha = expr(date("Y:m:d"));')
 *     4) Posibilidad de incluir ficheros en linea, si se permite. Es diferente a como
 *        se hace en CSS, ya que se trae desde el servidor
 *        (ej. '@incluir(definiciones_variables.css);')
 *     5) Crear nuevas definiciones de CSS por extensión a 
 *        reglas ya existentes
 *        (ej. '.texto_pequeno{font-size:0.8em} .texto_pequeno_rojo extender(.texto_pequeno){color:red}')
 *     6) Comentarios posibles con '// ... '
 *        (ej. 'width: 120px; // esto es un comentario')
 * Por supuesto, La sintaxis XCS es opcional, y la clase se puede usar para 
 * comprimir y decorar la sintaxis dada. Dependiendo de si se ha activado las
 * banderas, la clase hará lo siguiente--> valor) lo que hace
 *     1) Dejar el CSS como es.
 *     2) Eliminar propiedades duplicadas, alfabetizarlas y 
 *        opcionalmente,alfabetizar también las reglas.
 *     3) Como el 2), pero además corta las líneas duplicadas nuevas
 *     4) Como el 3), pero además recortará cualquier comentario CSS
 *  	  y quita los espacios sobrantes para dejar solo uno
 */
 define('ZF_XCS_SIN_COMPRESION',0);
 define('ZF_XCS_COMPRESION_LEGIBLE', 1);
 define('ZF_XCS_COMPRESION_LEIBLE', 2);
 define('ZF_XCS_COMPRESION_TOTAL', 3);
class zen_xcs {
	/**
	 * Nivel de compresion
	 * @var int
	 */
	var $nivelCompresion = ZF_XCS_COMPRESION_LEIBLE;
	/**
	 * Permitir el recorte de comentarios de CSS?
	 * Afecta a la alfabetización de las reglas CSS con la compresión
	 * se establecerá con ZF_XCS_COMPRESION_LEGIBLE
	 * @var str
	 */
	var $mantenerComentarios = false;
	/**
	 * Permite y procesa expresiones XCS del tipo `expr(...)`
	 * @internal Atención: implica EVALUAR las expresiones!!
	 * @var bool
	 */
	var $permitirExpresiones = false;
	/**
	 * Permite y procesa las expresiones XCS `@incluir()`
	 * @internal Atención: implica EVALUAR las expresiones!!
	 * @var bool
	 */
	var $permitirFicherosExternos = true;
	/* ======================== */
	/* === Nivel interno    === */
	/* ======================== */
	/**
	 * XCS prefijo de nombre de variables, has de cambiar $this->expresionNombre también
	 * @see $this->expresionNombre
	 * @var str
	 */
	var $prefijoVariables = '!';
	/**
	 * XCS expresion regular para las variables.
	 * Si has cambiado se cambia se ha de cambiar $this->prefijoVariables también
	 * @var str
	 */
	var $expresionNombre = '![A-Za-z0-9_-]+';
	/**
	 * XCS Expresión regular para los valores de las variables
	 * @var str
	 */
	var $expresionValor = '[!#@:()~.%$0-9A-Za-z ]+';
	/**
	 * XCS expresión regular para los comentarios
	 * @var str
	 */
	var $expresionComentario = '\/\/';
	/**
	 * Expresión regular para la función de incluir()
	 * XCS `@incluir()`
	 * @var str
	 */
	var $expresionIncluir = '\@incluir\s*\((.*?)\);';
	/**
	 * Expresión regular para la expresión dada por XCS
	 * XCS `expr()`
	 * @var str
	 */
	var $expresionRegular = 'expr\s*\(([^;]+)\)';
	/**
	 * Expresión regular para la función de extender dada en XCS
	 * XCS `extender(definicion_regla)`
	 * @var str
	 */
	var $expresionExtension = 'extender\s*\((.*?)\)\s*\{';
	/* ============================== */
	/* === Almacenamiento interno === */
	/* ============================== */
	/**
	 * Cadena XCS
	 * @var str
	 */
	var $css = null;
	/**
	 * Las variables del fichero
	 * @var array
	 */
	var $variablesCSS = array();
	/**
	 * Array interno de ficheros que usa la 
	 * función XCS `@incluir()`
	 * @var array
	 */
	var $ficheros = array();
	/* ============================ */
	/* ====== Métodos ============= */
	/* ============================ */
	/* ================ */
	/* === Creadores=== */
	/* ================ */
	/**
	 * cargar.
	 * Carga los contenidos de un fichero dado y crea el analizador.
	 * @throws Exception si no se especifica una ruta correcta
	 * @param str $fichero ruta del XCS/CSS
	 * @param int $nivelCompresion Opcional
	 * @return zen_xcs
	 */
	function &cargar ($fichero) {
		if (!file_exists($fichero)) trigger_error(_("No existe el fichero: ").$fichero);
		return new zen_xcs(
			file_get_contents($fichero)
		);
	}
	/**
	 * Constructor
	 * @param str $css XCS/CSS str a analizar/comprimir.
	 */
	function zen_xcs($css = false) {
		$this->ponerCSS($css);
	}
	/* =============== */
	/* === Estalecer:= */
	/* =============== */
	/**
	 * Pone la cadena XCS/CSS.
	 * @param str $css
	 * @return zen_xcs $this
	 */
	function &ponerCSS ($css) {
		$this->css = $css;
		return $this;
	}
	/**
	 * Poner una variale XCS.
	 * @param str $nombre Variable nombre
	 * @param mixed $valor Variable valor
	 * @return zen_xcs $this
	 */
	function &ponerVariable ($nombre, $valor) {
		if ($this->prefijoVariables == substr($nombre, 0, strlen($this->prefijoVariables)))
			$nombre = substr($nombre, strlen($this->prefijoVariables));
		$this->variablesCSS[$nombre] = $valor;
		return $this;
	}
	/**
	 * Establece un nivel de compresión para el analizador XCS
	 * @param int
	 * @return zen_xcs $this
	 */
	function &ponerNivelCompresion ($cl) {
		if (is_int($cl)) $this->nivelCompresion = $cl;
		return $this;
	}
	/**
	 * Pone la bandera para recortar comentarios fuera
	 * @param bool $c
	 * @return zen_xcs $this
	 */
	function &ponerNivelComentarios ($c) {
		if ($c) $this->mantenerComentarios = true;
		else $this->mantenerComentarios = false;
		return $this;
	}
	/**
	 * Establecer la bandera interna para la función XCS `expr(...)`
	 * @param bool $a
	 * @return zen_xcs $this
	 */
	function &ponerNivelExpresiones ($a) {
		if ($a) $this->permitirExpresiones = true;
		else $this->permitirExpresiones = false;
		return $this;
	}
	/**
	 * Establecer la bandera interna para la función XCS `@incluir()`
	 * @param bool $a
	 * @return zen_xcs $this
	 */
	function &ponerNivelFicherosExternos ($a) {
		if ($a) $this->permitirFicherosExternos = true;
		else $this->permitirFicherosExternos = false;
		return $this;
	}
	/* =============== */
	/* === Obtener === */
	/* =============== */
	/**
	 * Obtiene un valor de una variable XCS
	 * @param str $nombre
	 * @return mixed
	 */
	function obtenerVariable ($nombre) {
		if ($this->prefijoVariables == substr($nombre, 0, strlen($this->prefijoVariables))) $nombre = substr($nombre, strlen($this->prefijoVariables));
		return @$this->variablesCSS[$nombre];
	}
	/**
	 * Obtiene todas las definiciones XCS
	 * @return array
	 */
	function obtenerVariables () {
		return $this->variablesCSS;
	}
	/**
	 * Obtiene una cadena CSS analizada
	 * @return str
	 */
	function obtenerCSS () {
		return $this->css;
	}
	/* ================== */
	/* === Procesar   === */
	/* ================== */
	/**
	 * Analiza el XCS interno para convertirlo en un CSS válido
	 * @return zen_xcs $this
	 */
	function &analizar () {
		if (!$this->css) return false;
		if ($this->permitirFicherosExternos) $this->incluirFicheros();
		$this->analizarDefiniciones();
		while (1) {
			if (!$this->reemplazarDefinicionesOperaciones()) break;
		}
		if ($this->permitirExpresiones) $this->evaluarExpresiones();
		/* analizar definiciones de nuevo, tras los operadores */
		/* y expresiones de substitucion */
		$this->analizarDefiniciones();
		while (1) {
			if (!$this->reemplazarDefiniciones()) break;
		}
		$this->evaluarExtensiones();
		$this->evaluarComentarios();
		return $this;
	}
	/**
	 * Comprime/embellece una cadena analizada CSS.
	 * @return zen_xcs $this
	 */
	function &comprimir () {
		if (!$this->css) return false;
		if ($this->nivelCompresion == ZF_XCS_SIN_COMPRESION) return $this;
		if ($this->nivelCompresion >= ZF_XCS_COMPRESION_LEGIBLE) {
			if (!$this->mantenerComentarios) $this->castellanizarSelectores();
			$this->castellanizarPropiedades();
		}
		if ($this->nivelCompresion >= ZF_XCS_COMPRESION_LEIBLE) {
			$this->css = preg_replace('/(\r?\n)+/', "\n", $this->css);
		}
		if ($this->nivelCompresion >= ZF_XCS_COMPRESION_TOTAL) {
			$this->css = preg_replace('/\/\*(.*?)\*\//s', '', $this->css); // quitar comentarios
			$this->css = preg_replace('/\s\s*/', ' ', trim($this->css)); // quitar espacios en blanco
			$this->css = preg_replace('/([A-Za-z])\s?\{\s?/', '\1{', $this->css); //quitar espacios de cerca de una llave
			$this->css = preg_replace('/([;:])\s/', '\1', $this->css); //quitar espacios de cerca de ; y :
		}
		return $this;
	}
	/* =========================== */
	/* =====     métodos     ===== */
	/* =========================== */
	/**
	 * Manejador para XCS -> `@incluir` 
	 * @throws Exception si no se encuetra el fichero
	 * @return bool
	 */
	function incluirFicheros () {
		$resul = $lineas = $archivos = array();
		preg_match_all('/' . $this->expresionIncluir . '/sm', $this->css, $resul);
		$lineas = $resul[0];
		$archivos = $resul[1];
		foreach ($archivos as $llave=>$archivo) {
			if (in_array($archivo, $this->ficheros)) continue;
			$archivo = trim($archivo);
			if (!file_exists($archivo)) trigger_error(_("No existe el fichero: %s").$archivo);
			$css = file_get_contents($archivo);
			$lineas[$llave] = $this->obtenerExpresionRegular($lineas[$llave], 1);
			$this->css = preg_replace('/' . $lineas[$llave] . '/', $css, $this->css);
			$this->ficheros[] = $archivo;
		}
		return true;
	}
	/**
	 * Encuentra y extrae variables simples de una cadena de definiciones XCS
	 * que se almacenan y pueden recuperarse con zen_xcs::obtenerVariable(name).
	 * @return bool
	 */
	function analizarDefiniciones () {
		$variables = array();
		$rx = '/(' . $this->expresionNombre . ')\s*=\s*(' . $this->expresionValor . ')\;/';
		preg_match_all($rx, $this->css, $variables);
		array_shift($variables);
		foreach ($variables[0] as $llave=>$nombre) {
			if ($this->prefijoVariables == substr($nombre, 0, strlen($this->prefijoVariables)))
			 $nombre = substr($nombre, strlen($this->prefijoVariables));
			if (!isset($this->variablesCSS[$nombre]))
			 $this->variablesCSS[$nombre] = $variables[1][$llave];
		}
		/* Quitar definiciones de variables */
		$this->css = preg_replace($rx, '', $this->css);
		return true;
	}
	/**
	 * Encontrar expresiones XCS a evaluar
	 * Solo tienen 2 operandos:
	 * 	1) los 2 son variables XCS, o
	 * 	2) una variable XCS, y un entero
	 * return bool True cuando cambia
	 */
	function reemplazarDefinicionesOperaciones () {
		$varOps = array();
		$anteriorCSS = $this->css;
		$rx = '/(' . $this->expresionNombre . '|[0-9]+)\s*([-+*\/])\s*(' . $this->expresionNombre . '|[0-9]+)/';
		preg_match_all($rx, $this->css, $varOps);
		foreach ($varOps[0] as $llave=>$op_actual) {
			$nombre1 = $varOps[1][$llave];
			$valor1 = ($this->obtenerVariable($nombre1)) ? $this->obtenerVariable($nombre1) : $nombre1;
			$op = $varOps[2][$llave];
			$nombre2 = $varOps[3][$llave];
			$valor2 = ($this->obtenerVariable($nombre2)) ? $this->obtenerVariable($nombre2) : $nombre2;
			$resul = $this->ejecutarOperaciones ($valor1, $op, $valor2);
			$op_actual = $this->obtenerExpresionRegular($op_actual);
			$this->css = preg_replace('/' . $op_actual . '/', $resul, $this->css);
		}
		/* Hemos substituido algo? */
		if ($anteriorCSS == $this->css) return false;
		return true;
	}
	/**
	 * Itera la hoja de estilos XC e intercambia los nombres de variales
	 * con sus valores almacenados internamente en la clase.
	 * Si no está definido el valor se deja tal cual
	 * @return bool True on change
	 */
	function reemplazarDefiniciones () {
		$anteriorCSS = $this->css;
		foreach ($this->variablesCSS as $nombre=>$valor) {
			$this->css = preg_replace('/' . $this->obtenerExpresionRegular($this->prefijoVariables . $nombre, 1) . '/', $valor, $this->css);
		}
		/* Se substituyó algo? */
		if ($anteriorCSS == $this->css) return false;
		return true;
	}
	/**
	 * Ejecuta expresiones simples XCS.
	 * Intenta respetar las unidades escritas.
	 * @return mixed $resul
	 */
	function ejecutarOperaciones ($v1, $op, $v2) {
		$resul = '';
		if (preg_match('/#[0-9ABCDEFabcdef]+/', $v1)) {
			/* Codigos de color */
			$val1 = intval(substr($v1, 1), 16);
			$val2 = intval(substr($v2, 1), 16);
			$resul = '#' . dechex(eval('return ' . $val1 . $op . $val2 . ';'));
		} else if (preg_match('/[0-9.]+(em|ex|px|pt)/', $v1)) {
			/* Medidas */
			$val1 = floatval($v1);
			$val2 = floatval($v2);
			$unidad = trim(preg_replace('/[0-9.]+/', '', $v1));
			$resul = eval('return ' . $val1 . $op . $val2 . ';') . $unidad;
		} else if (preg_match('/#[0-9ABCDEFabcdef]+/', $v2)) {
			/* Valor con expresion y codigo de color */
			$val1 = intval($v1);
			$val2 = intval(substr($v2, 1), 16);
			$resul = '#' . dechex(eval('return ' . $val1 . $op . $val2 . ';'));
		} else if (preg_match('/[0-9.]+(em|ex|px|pt)/', $v2)) {
			/* Valor con expresion y medida */
			$val1 = floatval($v1);
			$val2 = floatval($v2);
			$unidad = trim(preg_replace('/[0-9.]+/', '', $v2));
			$resul = eval('return ' . $val1 . $op . $val2 . ';') . $unidad;
		} else {
			$resul = @eval('return ' . $v1 . $op . $v2 .';');
		}
		return $resul;
	}
	/**
	 * Ejecuta expresiones XCS complejas
	 * Es peligroso porque usa `eval`,
	 * este paso puede ser prohibido en la clase con
	 * zen_xcs::permitirExpresiones en (bool)False.
	 * @return bool
	 */
	function evaluarExpresiones () {
		$resul = $exprs = $evals = array();
		preg_match_all ('/' . $this->expresionRegular . '/sm', $this->css, $resul);
		$exprs = $resul[0];
		$evals = $resul[1];
		foreach ($evals as $llave=>$eval) {
			$res = eval('return ' . $eval . ';');
			$exprs[$llave] = $this->obtenerExpresionRegular($exprs[$llave], 1);
			$this->css = preg_replace(
				'/' . $exprs[$llave] . '/',
				$res, $this->css
			);
		}
		return true;
	}
	/**
	 * Procesa la función XCS `extender(definicion_regla)`
	 * @return bool
	 */
	function evaluarExtensiones () {
		$encontrados = $lineas = $extens = array();
		preg_match_all('/' . $this->expresionExtension . '/sm', $this->css, $encontrados);
		$lineas = $encontrados[0];
		$extens = $encontrados[1];
		foreach ($extens as $llave=>$exten) {
			$exten = trim($exten);
			$rule = array();
			if (preg_match('/^' . $exten . '\s*\{(.*?)\}/sm', $this->css, $rule)) {
				$lineas[$llave] = $this->obtenerExpresionRegular($lineas[$llave], 1);
				$this->css = preg_replace('/' . $lineas[$llave] . '/', "{\n\t".trim($rule[1]), $this->css);
			}
		}
		return true;
	}
	/**
	 * Convierte una linea de comentarios XCS (// ...) en una aceptable de CSS
	 * @return bool
	 */
	function evaluarComentarios () {
		$this->css = preg_replace(
			'/' . $this->expresionComentario . '(.*?)\r?\n/',
			"/* \\1 */\n",
			$this->css
		);
		return true;
	}
	/**
	 * Alfabetiza propiedades CSS
	 * Además quita las propiedades duplicadas properties, dejando solo
	 * la ultima (CASCADA) como hace CSS.
	 * @return bool
	 */
	function castellanizarPropiedades () {
		$encontrados = $lineas = $bloques = array();
		preg_match_all('/\{(.*?)\}/sm', $this->css, $encontrados);
		$lineas = $encontrados[0];
		$bloques= $encontrados[1];
		foreach ($bloques as $llave=>$propBlock) {
			$props = explode("\n", trim($propBlock));
			$props = array_map('trim', $props);
			/* Destroir propiedades repetidas */
			$def_propiedades = array();
			for ($i = count($props); $i>=0; $i--) {
				$def = $val = false;
				@list($def,$val) = @explode(':', $props[$i]);
				if (!$def || !$val) continue;
				if (in_array($def, $def_propiedades)) unset($props[$i]);
				else $def_propiedades[] = $def;
			}
			/* Volver a alfabetizar las propiedades */
			sort($props);
			$props = join("\n\t", $props);
			$lineas[$llave] = $this->obtenerExpresionRegular($lineas[$llave]);
			$this->css = preg_replace('/' . $lineas[$llave] . '/', "{\n\t$props\n}\n", $this->css);
		}
		return true;
	}
	/**
	 * Tries to alphabetize CSS rules.
	 * @Warning: kills comments outside rules!
	 * @return bool
	 */
	function castellanizarSelectores () {
		$reglas = array();
		$encontrados = preg_split('/^(.*\s?\{)\s*$/m', $this->css, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
		for ($i=0; $i<count($encontrados); $i++) {
			if (preg_match('/\{/', $encontrados[$i])) {
				$reglas[$i] = $encontrados[$i] . $encontrados[$i+1];
				$i++;
			} else {
				$rest[$i] = $encontrados[$i];
			}
		}
		sort($reglas);
		$this->css = join("\n", $reglas);
		return true;
	}
	/**
	 * Método ayudante de escape
	 * @param str $str cadena a escapar
	 * @param bool $usar_quote Llamar a preg_quote?
	 * @return str cadena escapada
	 */
	function obtenerExpresionRegular ($str, $usar_quote=false) {
		if ($usar_quote) $str = preg_quote($str);
		$str = preg_replace('/\//', '\/', $str);
		$str = preg_replace('/\*/', '\*', $str);
		if (!$usar_quote) $str = preg_replace('/\$/', '\\\$', $str);
		return $str;
	}
}
?>