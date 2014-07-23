<?php 
class markers extends zen_modelo_datos {
	/**  * @var ruralvia  */ 
	var $padre;
	/**
	 * @var html_markers
	 */
	var $html;
	/**  * @var array  */
	var $secciones = array(
	"venta"=>TITULO_VENTA,
	"alquiler"=>TITULO_ALQUILER,
	"compra"=>TITULO_COMPRA,
	 "trueque"=>TITULO_TRUEQUE,
	 "traspaso"=>TITULO_TRASPASO,
//	 "gratis"=>TITULO_GRATIS,
	 "autopromo"=>TITULO_AUTOPROMO);
	/**  * @var array  */
	var $tipos = array("hotel"=>
	 TITULO_HOTEL,"parcela"=>
	 TITULO_PARCELA,"alojamiento"=>
	 TITULO_ALOJAMIENTO,"casa"=>
	 TITULO_CASA,"finca"=>
	 TITULO_FINCA,"propiedad"=>
	 TITULO_PROPIEDAD);
	/**  * Constructor  *  
	 * * @param ruralvia $padre  
	 * * @return markers  
	 * 
	 */ 
	function markers(&$padre){
		parent::zen_modelo_datos($padre,"","contenidos");

	}

	/**  * Devuelve el IDC de un contenido con una $url_amiga unica  
	 * * @param str $url_amiga  
	 * * @return int  
	 * */ 
	function existe_url_amiga($url_amiga)
	{
		return $this->
		bd->
		seleccion_unica("idc from contenidos where url_amiga='".addslashes($url_amiga)."'");

	}

	/** * Devuelve un array con el idc de la categoria y el idc del contenido 
	 * * $condiciones_where han de ser de la forma 
	 * * " AND CONDICION " * o   : " OR  CONDICION " 
	 * * @param int $idc * @param str $condiciones_where 
	 * * @return array */ 
	function existe_categoria($idc,$condiciones_where="")
	{
		$cat = intval(      
		 $this->
		  bd->
		   seleccion_unica("cat.idc as idc from categorias cat inner join contenidos con on ".
			"cat.idc=con.categoria where con.idc=".        
			intval($idc)." ".$condiciones_where." limit 1"       
		 )   
		);
		$this->
		bd->
		mostrar_ultima_consulta();
		if ($cat)    return (array($idc,$cat));
		else     return null;

	}

	/**  * Devuelve el nombre de una categoria con un $idc identificador dado en el $idioma especificado  *  * @param int $idc  * @param str $idioma  * @return str  */ function nombre_categoria($idc,$idioma="es")
	{
		return $this->
		bd->
		seleccion_unica("nombre_".$idioma." from categorias where idc=".intval($idc));

	}

	/**  * Comprueba la existencia ,a partir de una url en $datos ,de una seccion y una categoria  *  * @param array $datos  * @return array  */ function comprobar_seccion_idc($datos)
	{
		$sec = zen_sanar($datos[1]);
		list($idc,$cat) = $this->
		existe_categoria($datos[2]," and seccion='$sec'");
		if (!$idc || !$cat) return false;
		$tipo= $this->
		bd->
		seleccion_unica("tipo from categorias where idc=".$cat);
		if ($sec && $idc && $cat && $tipo)   return array($sec,$idc,$tipo,$cat);
		else    return false;

	}

	function comprobar_seccion_categoria($datos)
	{
		if (!isset($datos[1]) || !array_key_exists($datos[1],$this->
		secciones))
		{
			return -1;

		}

		$sec = $datos[1];
		$idc = $this->
		bd->
		seleccion_unica("idc from categorias where idc=".intval($datos[2])." and seccion='".$sec."'");
		if (!$idc) return -2;
		$tipo= $this->
		bd->
		seleccion_unica("tipo from categorias where idc=".$idc);
		return array($sec,$idc,$tipo);

	}


}

?>