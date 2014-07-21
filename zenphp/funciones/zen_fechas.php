<?php
/**
 * zen_fechas.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * 
 * @brief Funciones para utilizar con fechas super útiles!
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
/**
 * Convierte fecha de mysql a normal
 *
 * @param date(Y-m-d) $fecha
 * @return date(d/m/Y)
 */
function zen_parsear_fecha_a_normal($fecha){
    ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
    return $mifecha[3]."/".$mifecha[2]."/".$mifecha[1];
}
/**
 * Convierte fecha de normal a mysql
 *
 * @param date(d/m/Y) $fecha
 * @return date(Y-m-d)
 */
function zen_parsear_fecha_a_mysql($fecha){
    ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
    $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1];
    return $lafecha;
}
/**
 * Filtra un texto como fecha del formato [0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4}
 *
 * @param str $fecha
 * @return str : ([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})
 */
function zen_parsearFechaFormato($fecha){
    ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
    $lafecha=$mifecha[1]."/".$mifecha[2]."/".$mifecha[3];
    return $lafecha;
}
/**
 * Formato para DS -> dia/mes/año
 * Formato para los demás ...
 *
 * @param str $quecosa
 * @param date $_fecha
 * @param str $idioma : codigo del idioma ,el directorio dentro de DIR_IDIOMAS
 * @return str||date
 */
function zen_parsearFechaTexto($quecosa='t',$_fecha="",$idioma=ZF_IDIOMA_DEFECTO) { //t = toda la fecha
    if (empty($fecha))  $fecha  = date('d/m/Y');
    if (empty($idioma)) $idioma = ZF_IDIOMA_DEFECTO;
    //require_once(ZF_DIR_CONTENIDO_ZEN.$idioma.'/zen_fecha.php'); //--> reemplazado por el fichero .po y .mo de idiomas ;)
    $diassemana = array(_("Domingo"),_("Lunes"),_("Martes"),_("Mi&eacute;rcoles"),_("Jueves"),_("Viernes"),_("S&aacute;bado"));
    $mesesano = array(_("Enero"),_("Febrero"),_("Marzo"),_("Abril"),_("Mayo"),_("Junio"),_("Julio"),_("Agosto"),_("Septiembre"),_("Octubre"),_("Noviembre"),_("Diciembre"));
    switch ($quecosa) {
    case 'd':
        return $diassemana[date('w')];
        break;
    case 'dm':
        return date('j') ;
        break;
    case 'm':
        return $mesesano[date('n')] - 1 ;
        break;
    case 'y':
        return date('Y') ;
        break;
    case 'DS': //Dia de la semana de una fecha en concreto
        $fecha = split('/',$_fecha);
        if (count($fecha)==3)
         return  $diassemana[date('w',mktime(0,0,0,$fecha[1],$fecha[0],$fecha[2]))]." ".$fecha[0]." ".ZF_TEXT_DE." ".
                $mesesano[intval($fecha[1])-1]." ".
                    ZF_TEXT_DEL." ".$fecha[2];
        else return _("Fecha no v&aacute;lida ").$_fecha;
        break;
    default: //toda la fecha completa
        return $diassemana[$diassemana[date('w')]].",".date('j')."/".($mesesano[date('n')] - 1)."/".date('Y');
        break;
    }
}
/**
 * Calcula la diferencia entre dos fechas y devuelve el número de dias resultante:
 * Pasar las fechas como cadenas en el formato: date('d/m/Y')
 *
 * @param str $fecha_ini
 * @param str $fecha_fin
 * @return str
 */
function zen_comparaFechas ($fecha_ini, $fecha_fin){
    //Inicialización
    $matriz_f_ini = explode ("/", $fecha_ini);
    $matriz_f_fin = explode ("/", $fecha_fin);   
    //Cálculo
    return gregoriantojd($matriz_f_fin[1], $matriz_f_fin[0], $matriz_f_fin[2]) - gregoriantojd($matriz_f_ini[1], $matriz_f_ini[0], $matriz_f_ini[2]);
}
/**
    * Función que calcula una fecha futura según la cantidad de días que se le proporcionen como argumento
    * Pasa la fecha en formato date('d/m/Y') con el caracter de entrada caracterEntrada y devuelve la suma
    * formateada con el caracter de salida caracterSalida.
    * Adaptación: Aoyama (LSCA. Israel E. Garcia)
    * @param date $fecha
    * @param int $dias
    * @param char $caracterEntrada
    * @param char $caracterSalida
    * @return date
    */
function zen_sumaFechas($fecha, $dias,$formato=2,$caracterEntrada="/",$caracterSalida="/")
{
    $corte = split($caracterEntrada,$fecha);
    $dia = $corte[0];
    $mes = $corte[1];
    $anio = $corte[2];
    $ultimo_dia = date( "d", mktime(0, 0, 0, $mes + 1, 0, $anio) ) ;
    $dias_adelanto = $dias;
    $siguiente = $dia + $dias_adelanto;
    if ($ultimo_dia < $siguiente)
    {
        $dia_final = $siguiente - $ultimo_dia;
        $mes++;
        if ($mes == '13')
        {
            $anio++;
            $mes = '01';
        }
        $fecha_final = $dia_final.$caracterSalida.$mes.$caracterSalida.$anio;
    }
    else
    {
        $fecha_final = $siguiente .$caracterSalida.$mes.$caracterSalida.$anio;
    }
    switch ($formato) {
        case 1:
            $r = split($caracterSalida,$fecha_final);
            return  $r[2].$caracterSalida.$r[1].$caracterSalida.$r[0]; //Y-m-d
            break;
        default:
            return $fecha_final; //d/m/Y
            break;
    }
}
?>