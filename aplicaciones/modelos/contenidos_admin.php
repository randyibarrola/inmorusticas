<?php
class contenidos_admin extends zen_modelo_datos {
 /**
  * @var cannabric_admin
  */
 var $padre;
 /**
  * @var html_contenidos_admin
  */
 var $html;
 /**
  * @param cannabric_admin $padre
  * @return contenidos_admin
  */
 function contenidos_admin(&$padre){
  parent::zen_modelo_datos($padre,"","contenidos");
 }
 /**
  * Devuelve una lista de categorias en un $idioma dado
  *
  * @param str $idioma
  * @return array
  */
 function categorias($idioma="es"){
  $r = $this->bd->seleccion("nombre_$idioma from categorias order by nombre_$idioma ASC");
  $cats = array();
  while ($r && $f = $this->bd->obtener_fila($r)) {
   array_push($cats,$f);
  }
  return $cats;
 }
 /**
  * Devuelve el nombre de una categoria con un $idc identificador dado en el $idioma especificado
  *
  * @param int $idc
  * @param str $idioma
  * @return str
  */
 function nombre_categoria($idc,$idioma="es"){
  return $this->bd->seleccion_unica("nombre_$idioma from categorias where idc=".intval($idc));
 }
 
 /**
 * Devuelve un array con el idc de la categoria y el idc del contenido
 * $condiciones_where han de ser de la forma
 * " AND CONDICION "
 * o   : " OR  CONDICION "
 *
 * @param int $idc
 * @param str $condiciones_where
 * @return array
 */
 function existe_categoria($idc,$condiciones_where="")
 {
   $cat = intval(
   	$this->bd->seleccion_unica(
   	 "cat.idc as idc from categorias cat inner join contenidos con on cat.idc=con.categoria where con.idc=".
   	 intval($idc)." ".$condiciones_where." limit 1"
   	)
   );
   if ($cat)
    return (array($idc,$cat));
   else 
    return null;
 }
 
}
?>