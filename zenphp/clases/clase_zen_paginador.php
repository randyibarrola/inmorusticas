<?php
/**
 * clase_zen_paginador.php
 * @author Juan Belon
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase que devuelve información de números de página para paginar resultados como lo hacen los buscadores [1]  _2_ ...  _3_   _4_
 */
/* +----------------------------------------------------------------------
   |                              
   |                      /        
   |  ___  ___  ___  ___ (___  ___ 
   |  __/ |___)|   )|   )|   )|   )
   | /__  |__  |  / |__/ |  / |__/ 
   |                |         |    
   | zenphp.es
   +----------------------------------------------------------------------*/
define('ZF_PAGINAS_POR_PAGINA',10);
/**
 * =========inicio paginador.php =========
<?php
    //generatar un array
    $resultados = range(0, 710);

    $resultadosPorPagina = 20; //20 results on a pagina
    $totalPaginas = ceil(count($resultados)/$resultadosPorPagina); //calcula el total de numero de paginas
    echo "total p&aacute;ginas = " . $totalPaginas . "<br />";

    $pagina = $_GET['p']; //obtener el numero de pagina
    echo "n&uacute;mero de p&aacute;gina = " . $pagina . "<br /><br />";

    //mostrar resultados
    $division_ini = ($pagina-1)*$resultadosPorPagina;
    $division = $resultadosPorPagina;
    echo "<pre>";
    print_r(array_division($resultados, $division_ini, $division));
    echo "</pre>";
    
    
    zen___carga_clase('zen_paginador');
    
    //parametros:
    //$pagina = pagina actual
    //$totalPaginas = numero total de paginas
    //3 = numeros a mostrar a la izquierda y a la derecha del numero de pagina actual
    $pag = new zen_paginador($pagina, $totalPaginas, 3);

    //la primera y ultima pagina siempre se muestran
    //necesitamos un separador entre la primera/ultima pagina asi como en medio de los numeros de pagina
    $separador = "...";
    foreach($pag->posiciones as $numero_pagi=>$tipo)
    {
        //cada numero tiene un tipo de 4 disponibles
        //  "actual" - numero de pagina actual;
        //  "enlace" - es el enlace a otros numeros de pagina
        //  "separadorDespues" - primer separador entre numeros de pagina (antes)
        //  "separadorAntes" - ultimo separador (despues de los numeros de pagina)
        switch($tipo)
        {
            case "actual": echo '&nbsp;' . $numero_pagi . '&nbsp';
                break;
                
            case "enlace": echo '&nbsp<a href="index.php?p=' . $numero_pagi . '">' . $numero_pagi . '</a>&nbsp;';
                break;
                
            case "separadorDespues": echo '&nbsp<a href="index.php?p=' . $numero_pagi . '">' . $numero_pagi . '</a>&nbsp;' . $separador . '&nbsp;';
                break;
                
            case "separadorAntes": echo '&nbsp;' . $separador . '&nbsp;<a href="index.php?p=' . $numero_pagi . '">' . $numero_pagi . '</a>';
                break;
        }
    }
?>
 * =========fin paginador.php =========
 *
 */
class zen_paginador {
	/**
	 * ¿Ha habido algún error?
	 *
	 * @var unknown_type
	 */
    var $error = false;
    /**
     * Donde se guardan las posiciones de las páginas, ver documentación
     *
     * @var array
     */
    var $posiciones = array();
    /**
     * Total de registros
     *
     * @var unknown_type
     */
    var $total_pags = 0;
    /**
     * Constructor de la clase paginadora
     *
     * @param int $pag_actual
     * @param int $total_pags
     * @param int $numero_indices
     * @return zen_paginador
     */
    function zen_paginador($pag_actual, $total_pags, $numero_indices){
        $this->comprobar($pag_actual, $total_pags);
        if($this->error == true){
            echo _("P&aacute;gina incorrecta")."\r\n"; 
            
        }
        $this->total_pags = intval($total_pags);
        if(($pag_actual <= ($numero_indices+1)) && ($total_pags <= ($numero_indices+1))){
            for($i=1; $i<=$total_pags; $i++) {
                if($pag_actual == $i) {
                    $this->posiciones[$i] = "actual";
                } else {
                    $this->posiciones[$i] = "enlace";
                }
            }    
        } elseif(($pag_actual <= ($numero_indices+1)) && ($total_pags > ($numero_indices+1)) && ($total_pags <= ($numero_indices*2+1))) {
            for($i=1; $i<=$total_pags; $i++) {
                if($pag_actual == $i) {
                    $this->posiciones[$i] = "actual";
                } else {
                    $this->posiciones[$i] = "enlace";
                }
            }
        } elseif(($pag_actual <= ($numero_indices+1)) && ($total_pags > ($numero_indices*2+1))) {
            for($i=1; $i<=($numero_indices*2+1); $i++) {
                if($pag_actual == $i) {
                    $this->posiciones[$i] = "actual";
                } else {
                    $this->posiciones[$i] = "enlace";
                }
            }
            $this->posiciones[$total_pags] = "separadorAntes";
        } elseif(($pag_actual > ($numero_indices+1)) && ($total_pags <= ($numero_indices*2+1))) {
            for($i=1; $i<=$total_pags; $i++) {
                if($pag_actual == $i) {
                    $this->posiciones[$i] = "actual";
                } else {
                    $this->posiciones[$i] = "enlace";
                }
            }    
        } elseif(($pag_actual > ($numero_indices+1)) && ($total_pags > ($numero_indices*2+1))) {
            $usar_separadorDespues = true;
            $usar_separadorAntes = true;
            
            if($pag_actual == ($numero_indices+2)) {
                $ancho_ini = 1;
                $usar_separadorDespues = false;
            } else {
                $ancho_ini = $pag_actual-$numero_indices;
                //$usar_separadorDespues = true;
            }
            
            if($pag_actual < ($total_pags-$numero_indices)) {
                if($pag_actual == ($total_pags-($numero_indices+1))){
                    $ancho_fin = $pag_actual+($numero_indices+1);
                    $usar_separadorAntes = false;
                } else {
                    $ancho_fin = $pag_actual+$numero_indices;
                }
            } else {
                $ancho_fin = $total_pags;
                $ancho_ini = ($total_pags-($numero_indices*2));
                $usar_separadorAntes = false;
            }
            
            if($usar_separadorDespues) {
                $this->posiciones[1] = "separadorDespues";
            }
    
            for($i=$ancho_ini; $i<=$ancho_fin; $i++) {
                if($pag_actual == $i)
                {
                    $this->posiciones[$i] = "actual";
                } else
                {
                    $this->posiciones[$i] = "enlace";
                }
            }
            
            if($usar_separadorAntes) {    
                $this->posiciones[$total_pags] = "separadorAntes";
            }
        }
    }
    /**
     * Comprueba que una página esté en el conjunto
     *
     * @param int $pagina
     * @param int $total_pags
     * @return bool
     */
    function comprobar($pagina, $total_pags=-1){
    	if ($total_pags==-1) $total_pags=$this->total_pags;
        if($pagina > $total_pags)
        {
            $this->error = true;
        }
        
        return $this->error;
    }
    /**
     * Devuelve el HTML para paginar resultados
     * @example $html = $paginador->html_paginado($_GET['pagina'],"index.php/listar/");
     *
     * @param int $pagina
     * @param str $enlace
     * @param bool $mostrar_info Si es true muestra el total y el número de páginas
     * @param int $resultados_por_pagina Si no se especifica se usa ZF_PAGINAS_POR_PAGINA definido en la misma clase
     * @return str
     */
    function html_paginado($pagina,$enlace,$mostrar_info=true,$resultados_por_pagina=ZF_PAGINAS_POR_PAGINA){
    	$totalPaginas = ceil($this->total_pags/$resultados_por_pagina); //calcula el total de numero de paginas
    	$html = "";
    	if ($mostrar_info)
    	{
    		$html  = _("Total p&aacute;ginas = ") . $totalPaginas . "<br />";
    	   	$html .= _("N&uacute;mero de p&aacute;gina = ") . $pagina . "<br /><br />";
    	}
    	//mostrar resultados
    	$division_ini = ($pagina-1)*$resultados_por_pagina;
    	$division = $resultados_por_pagina;
	    //la primera y ultima pagina siempre se muestran
	    //necesitamos un separador entre la primera/ultima pagina asi como en medio de los numeros de pagina
	    $separador = "...";
	    foreach($this->posiciones as $numero_pagi=>$tipo)
	    {
        	//cada numero tiene un tipo de 4 disponibles
    	    //	"actual" - numero de pagina actual;
	        //  "enlace" - es el enlace a otros numeros de pagina
        	//  "separadorDespues" - primer separador entre numeros de pagina (antes)
        	//  "separadorAntes" - ultimo separador (despues de los numeros de pagina)
        	switch($tipo)
        	{
	            case "actual": $html.= '<span class="actual">' . $numero_pagi . '</span>';
                	break;
	                
            	case "enlace": $html .= '<a href="'.$enlace.$numero_pagi . '">' . $numero_pagi . '</a>';
	                break;
                	
            	case "separadorDespues": $html .= '<a href="'.$enlace.$numero_pagi . '">' . $numero_pagi . '</a>' . $separador;
	                break;
                	
            	case "separadorAntes": $html .= $separador . '<a href="'.$enlace . $numero_pagi . '">' . $numero_pagi . '</a>';
	                break;
        	}
    	}
    	return $html;
    }
    
}

?>