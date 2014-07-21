<?php
/**
 * @file zen_general.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @brief  Fichero de funciones generales para el conjunto de librerias de zenphp.
 * Nota: las funciones con 3 guiones bajos (___) son usadas por zenphp, ojo con los cambios. ;-)
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
/**
* Borra los espacios,retornos de carro,tabulaciones...
*
* @param str $cadena
* @param str $reemplazo
* @return str
*/
function zen_borraEspacios($cadena,$reemplazo="") {
	return preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+|[\n]+|[\']/",$reemplazo,$cadena);
}

/**
 * Elimina el HTML de la variable pasada y la devuelve modificada (filtrada)
 *
 * @param str $variable
 * @return str
 */
function zen_borraHTML($variable){
	/* $variable = preg_replace('/<(.|\s)*?>/', '', $variable); */
	$variable = preg_replace('/<(.*)>/', '', $variable);
	$variable = str_replace('<','',$variable);
	$variable = str_replace('>',"",$variable);
	return $variable;
}

/**
 * Le pasa html entities y addslashes para guardar html y caracteres extraï¿½os en la bd
 * @param str :html $var
 *
 * @return str
 */
function zen_parsear_html($var){
	return htmlentities(addslashes($var));
}
/**
 * Devuelve el texto a su estado original de la bd a html
 *
 * @param str $var
 * @return str : html
 */
function zen_reconstruye_html($var){
	return stripslashes(html_entity_decode($var));
}


/**
 * Obtiene el valor adecuado de la variable desde GET o POST. Si cookies = true tambiï¿½n comprueba de la cookie...
 *
 * @param str $nombre
 * @param bool $cookies
 * @param int $defecto : valor por defecto
 * @return int valor de la variable en entero
 */
function zen_parsearEntero($nombre,$defecto=0,$cookies=false) {
	if (isset( $_GET[$nombre]) ) {
		$valor = intval($_GET[$nombre]);
	} elseif (isset( $_POST[$nombre] ) ) {
		$valor = intval($_POST[$nombre]);
	} elseif ($cookies && isset($_COOKIE[$nombre])) {
		$valor = intval($_COOKIE[$nombre]);
	} else {
		$valor = $defecto;
	}
	return $valor;
}
/**
 * Sanea y devuelve todo el GET evitando las llaves pasadas en una lista como cadena entre comas ("len,idp,...")
 *
 * @param str $evitar
 * @return str
 */
function zen_parsear_get_enviado($evitar=""){
	if (!isset($_GET)) return "";
	$evitar = split(",",$evitar);
	$cadena ="?";
	foreach ($_GET as $llave => $valor) {
		if (!in_array($llave,$evitar)){
			if ($cadena!="?") $cadena .="&";
			$cadena .= sanar($llave)."=".sanar($valor);
		}
	}
	return $cadena;
}

/**
 * Limpia todo el html y las comillas de una cadena devolviendola filtrada
 *
 * @param str $variable
 * @return str
 */
function zen_sanar($variable,$limit=0){
	return $limit>0?substr(addslashes(zen_borraHTML($variable)),0,$limit):addslashes(zen_borraHTML($variable));
}
/**
 * Serializa un array usando comas para la cadena resultante
 *
 * @param str $array
 * @return str
 */
function zen_serializar(&$array){
	$str ="";
	$n = count($array);
	for ($i=0; $i<$n; $i++){
		$str .= $array[$i];
		if ($i<$n) $str .= ",";
	}
	return $str;
}
/**
 * Deserializa una cadena en un array usando como patrï¿½n la coma
 *
 * @param str $cadena
 * @return array
 */
function zen_deserializar($cadena){
	$r =  split(",",$cadena);
	if (!is_array($r)) return array();
	for ($i=0; $i<count($r); $i++){
		if (empty($r[$i])) unset($r[$i]);
	}
	return $r;
}

/**
 * Envia un correo en UTF8 y HTML con los parametros pasados y devuelve el resultado del envio
 *
 * @param str : mail $para
 * @param str  $asunto
 * @param str : html $mensaje
 * @param str $mail_respuesta
 * @return bool
 */
function zen_enviarCorreo($para,$asunto,$mensaje,$mail_respuesta=""){
	$headers = "From: ".ZF_SITIO_WEB." <".ZF_CORREO_ADMIN.">\r\n" .
	"Reply-To: $mail_respuesta <$mail_respuesta>\n".
	'X-Mailer: PHP/' . phpversion() . "\r\n" .
	"MIME-Version: 1.0\r\n" .
	"Content-Type: text/html; charset=utf-8\r\n" .
	"Content-Transfer-Encoding: 8bit\r\n\r\n";
	return mail($para, $asunto, $mensaje, $headers);
}

/**
 * Recorta los elementos de $datos con llaves pasadas en $campos como cadena separada por comas, en $maximo caracteres...
 *
 * @param array &$datos
 * @param str<"nombre1,nombre2...">||array $campos
 * @param int $maximo
 * @return boolean
 */
function zen_recortar_long_texto_en_array(&$datos,$campos,$maximo){
	if (!is_array($campos)) $campos = split(",",$campos);
	$maximo = intval($maximo);
	if ($maximo<=0) return false;

	for ($i=0; $i<count($campos); $i++){
		$datos[$campos[$i]] = substr($datos[$campos[$i]],0,$maximo);
	}

	return true;
}

/**
 * Recorta los elementos de $datos con llaves pasadas en $campos como cadena separada por comas, en $maximo palabras...
 *
 * @param array &$datos
 * @param str<"nombre1,nombre2...">||array $campos
 * @param int $maximo
 * @return boolean
 */
function zen_recortar_long_texto_en_array_por_palabras(&$datos,$campos,$maximo){
	if (!is_array($campos)) $campos = split(",",$campos);
	$maximo = intval($maximo);
	if ($maximo<=0) return false;
	for ($i=0; $i<count($campos); $i++){
		$datos[$campos[$i]] = zen_recortar_longitud_texto($datos[$campos[$i]],$maximo);
	}

	return true;
}
/**
 * Recorta el $texto en el numero de palabras pasado en $num_palabras
 *
 * @param str $texto
 * @param int $num_palabras
 * @return str
 */
function zen_recortar_longitud_texto(&$texto,$num_palabras){
	if (!is_string($texto)) return "";
	$palabras = split(" ",$texto,$num_palabras+1);
	$r = "";
	$n = count($palabras);
	for ($k=0; ($k<$n && $k<($num_palabras-1)); $k++){
		$r .= " ".$palabras[$k];
	}
	return $r;
}

/**
 * Utiliza una expresion regular para comprobar que el $correo pasado es realmente un correo electronico
 *
 * @param str $correo
 * @return bool
 */
function zen_es_correo($correo){
	return preg_match(
	'/^[\ a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,6}$/i',
	$correo
	);
}
/**
 * Función para mapear valores en un array MANTENIENDO los indices del mismo :)
 *
 * @param callback $param1
 * @param array $param2
 * @param array $param3
 * @return array
 * @example Ejemplo:
<?php
    $arr1 = array(
        '3' => 'a',
        '4' => 'b',
        '5' => 'c'
        );

    $arr2 = array(
        '3' => 'd',
        '4' => 'e',
        '5' => 'f'
        );

    $arr3 = zen_mapear_valores(create_function('$a,$b','return $a.$b;'),$arr1,$arr2);

    print_r($arr3);

?>

El resultado obtenido es:

Array
(
    [3] => ad
    [4] => be
    [5] => cf
)
 */
function zen_mapear_valores($param1,$param2,$param3=NULL) {
	$res = array(); //resultado devuelto
	if ($param3 !== NULL) {
		foreach(array(2,3) as $nombre){
			if (!is_array(${'param'.$nombre})){
                          trigger_error(__FUNCTION__.'(): '.sprintf(_("El argumento %s debe ser un array"),$nombre),E_USER_WARNING);
				return;
			}
		}
		foreach($param2 as $llave => $val)	{
			$res[$llave] = call_user_func($param1,$param2[$llave],$param3[$llave]);
		}
	}else {
		if (!is_array($param2))	{
			trigger_error(__FUNCTION__.'(): '._("Argumento #2 ha de ser un array"),E_USER_WARNING);
			return;
		}
		foreach($param2 as $llave => $val){
			$res[$llave] = call_user_func($param1,$param2[$llave]);
		}
	}
}
/**
 * Función para mapear un array con una función de usuario con la utilidad de mantener las llaves del array original
 *
 * @param mixed $param1 callback (función)
 * @param array $param2
 * @param bool $usar_llaves_como_valor
 * @return array
 */
function zen_mapear_valores_simple($param1,$param2,$usar_llaves_como_valor=false) {
//A un array le pasamos una función y mantenemos las
	$res = array(); //resultado devuelto
	if (!is_array($param2))	{
                trigger_error(__FUNCTION__.'(): '._("Argumento #2 ha de ser un array"),E_USER_WARNING);
		return;
	}
	if (!is_callable($param1)) {
                trigger_error(__FUNCTION__.'(): '._("Argumento #1 ha de ser una funci&oacute;n"),E_USER_WARNING);
		return false;
	}
	foreach($param2 as $llave => $val){
		$res[$llave] = $usar_llaves_como_valor?call_user_func($param1,$llave):call_user_func($param1,$param2[$llave]);
	}

	return $res;
}
?>