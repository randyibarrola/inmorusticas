<?php
/**
 * clase_zen_JSON.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 1.0
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase para serilizar objetos o matrices PHP a notación JSON y viceversa
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
 * Para el analizador JSON: estado inicial
 */
define('ZF_JSON_EN_INICIO',0);
/**
 * Para el analizador JSON: estado->analizando cadena
 *
 */
define('ZF_JSON_EN_CADENA',1);
/**
 * Para el analizador JSON: analizando objeto
 *
 */
define('ZF_JSON_EN_OBJETO',2);
/**
 * Para el analizador JSON: analizando expresión
 *
 */
define('ZF_JSON_EN_EXPRESION',3);
/**
 * Para el analizador JSON: analizando asignación
 *
 */
define('ZF_JSON_EN_ASIGNACION',4);
/**
 * Para el analizador JSON: estado final
 *
 */
define('ZF_JSON_EN_FINAL',5);
/**
 * Para el analizador JSON: Analizando array 
 *
 */
define('ZF_JSON_EN_MATRIZ',6);
/**
 * Clase JSON para importar/exportar datos con PHP y AJAX
 *
 */
class zen_JSON
{
    /**
    *    Errores cometidos al analizar sintácticamente
    *    
    *    @var bool
    *    @access private
    */
    var $error;
    /**
    * Constructor
    *
    * @return JSON
    */
    function zen_JSON() {
        $this->error = "";
    }

    /**
    *    serializar un objeto PHP o una matriz en una notación JSON
    *
    *    @param object||array a serializar
    *    @return str JSON.
    */
    function serializar($obj) {
        if ( is_object($obj) ) {
            $propiedades = array();
            $vars = get_object_vars($obj);
            foreach ($vars as $k => $v) {
                $propiedades[] = $this->serializar_elemento( $k,$v );
            }
            return "{".implode(",",$propiedades)."}";
        } else if ( is_array($obj) ) {
            return $this->serializar_elemento('',$obj);
        }
    }

    /**
    *    Deserializar
    *
    *    Transforma una cadena JSON en un objeto PHP para devolverlo
    *    @access private 
    *    @param  str $cadena JSON
    *    @return object||array||false
    */
    function deserializar($cadena) {
        $resul = new stdClass; $i =""; $tipo = "";
        while (  $f = $this->obtenerToken($cadena,$i,$tipo)  ) {
            switch ( $tipo ) {
                case ZF_JSON_EN_MATRIZ:
                    $tmp = $this->deserializarMatriz($cadena);
                    $resul = $tmp[0];
                    break;
                case ZF_JSON_EN_OBJETO:
                    $g=0; $tipo_var = "";
                    do  {
                        $nombre_var = $this->obtenerToken($f,$g,$tipo_var);
                        if ( $tipo_var != ZF_JSON_EN_CADENA )  {
                            return false; /* error analizando! */
                        }
                        $this->obtenerToken($f,$g,$tipo_var);
                        if ( $tipo_var != ZF_JSON_EN_ASIGNACION) return false;
                        $valor = $this->obtenerToken($f,$g,$tipo_var);

                        if ( $tipo_var == ZF_JSON_EN_OBJETO) {
                            $resul->$nombre_var = $this->deserializar( "{".$valor."}" );
                            $g--;
                        } else if ($tipo_var == ZF_JSON_EN_MATRIZ) {
                            $resul->$nombre_var = $this->deserializarMatriz( $valor);
                            $g--;
                        } else
                        $resul->$nombre_var = $valor;

                        $this->obtenerToken($f,$g,$tipo_var);
                    } while ( $tipo_var == ZF_JSON_EN_FINAL);
                    break;
                default:
                    $this->error = true;
                    break 2;
            }
        }
        return $resul;
    }

    /**
    *    JSON Analizador sintáctico
    *
    *    Transforma una matriz de json en una de PHP 
    *    @access private
    *    @param str
    *    @return array
    */
    function deserializarMatriz($cadena) {
        $r = array(); $i =""; $tipo = "";
        do {
            $f = $this->obtenerToken($cadena,$i,$tipo);
            switch ( $tipo ) {
                case ZF_JSON_EN_CADENA:
                case ZF_JSON_EN_EXPRESION:
                    $r[] = $f;
                    break;
                case ZF_JSON_EN_OBJETO:
                    $r[] = $this->deserializar("{".$f."}");
                    $i--;
                    break;
                case ZF_JSON_EN_MATRIZ:
                    $r[] = $this->deserializarMatriz($f);
                    $i--;
                    break;

            }
            $this->obtenerToken($cadena,$i,$tipo);
        } while ( $tipo == ZF_JSON_EN_FINAL);

        return $r;
    }

    /**
    *  obtenerToken
    *
    *  Devuelve al analizador un token válido y el tipo del mismo desde la cadena $e
    *  Si falla devuelve false
    *    
    *  @access private
    *  @param str $cadena Cadena de donde se extrae el token
    *  @param int $i  Start position to search next token
    *  @param int $estado Variable to get the token type
    *  @return str|bool Token in string or false on error.
    */
    function obtenerToken($cadena, &$i, &$estado) {
        $estado = ZF_JSON_EN_INICIO;
        $fin = -1;
        $inicio = -1;
        while ( $i < strlen($cadena) && $fin == -1 ) {
            $v = $cadena[$i];
            switch( $v ) {/* objetos */
                case "{":
                case "[":
                    $etiqueta = $v;
                    $fin_etiqueta = $etiqueta == "{" ? "}" : "]";
                    if ($estado == ZF_JSON_EN_INICIO){
                        $inicio = $i+1;
                        switch ($estado) {
                            case ZF_JSON_EN_INICIO:
                                $aux = 1; /* necesario para iterar objetos */
                                $estado = $etiqueta == "{" ? ZF_JSON_EN_OBJETO : ZF_JSON_EN_MATRIZ;
                                break;
                            default:
                                break 2; /* fin del switch y el while */
                        }
                        while ( ++$i && $i < strlen($cadena) && $aux != 0 ) {
                            switch( $cadena[$i] ) {
                                case $etiqueta:
                                    $aux++;
                                    break;
                                case $fin_etiqueta:
                                    $aux--;
                                    break;
                            }
                        }
                        $fin = $i-1;
                    }
                    break;

                case '"':
                case "'":
                    $estado = ZF_JSON_EN_CADENA;
                    $buf = "";
                    while ( ++$i && $i < strlen($cadena) && $cadena[$i] != '"' ) {
                        if ( $cadena[$i] == "\\")
                        $i++;
                        $buf .= $cadena[$i];
                    }
                    $i++;
                    return eval('return "'.str_replace('"','\"',$buf).'";');
                    break;
                case ":":
                    $estado = ZF_JSON_EN_ASIGNACION;
                    $fin = 1;
                    break;
                case "n":
                    if ( substr($cadena,$i,4) == "null" ) {
                        $i=$i+4;
                        $estado = ZF_JSON_EN_EXPRESION;
                        return NULL;
                    }
                    else break 2; /* fin del switch y el while */
                case "t":
                    if ( substr($cadena,$i,4) == "true") {
                        $estado = ZF_JSON_EN_EXPRESION;
                        $i=$i+4;
                        return true;
                    }else break 2; /* fin del switch y el while */
                    break;
                case "f":
                    if ( substr($cadena,$i,4) == "false") {
                        $estado = ZF_JSON_EN_EXPRESION;
                        $i=$i+4;
                        return false;
                    }
                    else break 2; /* fin del switch y el while */
                    break;
                case ",":
                    $estado = ZF_JSON_EN_FINAL;
                    $fin = 1;
                    break;
                case " ":
                case "\t":
                case "\r":
                case "\n":
                    break;
                case "+":
                case "-":
                case ($v>=0 && $v<=9):
                case '.':
                    $estado = ZF_JSON_EN_EXPRESION;
                    $inicio = (int)$i;
                    if ( $cadena[$i] == "-" || $cadena[$i] == "+")
                    $i++;
                    for ( ;  $i < strlen($cadena) && (is_numeric($cadena[$i]) || $cadena[$i] == "." || strtolower($cadena[$i]) == "e") ;$i++){
                        $n = $i+1 < strlen($cadena) ? $cadena[$i+1] : "";
                        $a = strtolower($cadena[$i]);
                        if ( $a == "e" && ($n == "+" || $n == "-"))
                        $i++;
                        else if ( $a == "e")
                        $this->error=true;
                    }

                    $fin = $i;
                    break 2; /* fin del switch y el while */
                default:
                    $this->error = true;

            }
            $i++;
        }

        return $inicio == -1 || $fin == -1 ? false : substr($cadena, $inicio, $fin - $inicio);
    }

    /**
    *    serializar_elemento
    *
    *    @param string $llave nombre de Variable elemento
    *    @param mixed $valor Valor de la variable
    *    @access private
    *    @return str
    */
    function serializar_elemento (  $llave = '', &$valor ) {
        $r = '';
        if ( $llave != '')$r .= "\"${llave}\" : ";
        if ( is_numeric($valor) ) {
            $r .= ''.$valor.'';
        } else if ( is_string($valor) ) {
            $r .= '"'.$this->pasarAcadena($valor).'"';
        } else if ( is_object($valor) ) {
            $r .= $this->serializar($valor);
        } else if ( is_null($valor) ) {
            $r .= "null";
        } else if ( is_bool($valor) ) {
            $r .= $valor ? "true":"false";
        } else if ( is_array($valor) ) {
            foreach($valor as $k => $v)
            $f[] = $this->serializar('',$v);
            $r .= "[".implode(",",$f)."]";
            unset($f);
        }
        return $r;
    }

    /**
    *    Convertir en cadena una variable
    *
    *    @param string $e Variable with an string value
    *    @access private
    *    @return string serializard variable
    */
    function pasarAcadena($e) {
        $r = array("\\","\r","\n","\t","'",'"');
        $v = array("\\\\",'\r','\n','\t','\'','\"');
        $e = str_replace($r, $v, $e);
        return $e;
    }
}
?>