<?php
/**
 * clase_zen_carrito.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase que contiene un gestor del carro de la compra en PHP con uso de sesiones, leer la documentación para más información ;)
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
/*
Carrito de la compra de zenphp
PARA USAR LA CLASE
@example
zen___carga_clase('zen_carrito');
$carro = new zen_carrito();
$carro = new zen_carrito();
$carro->insertarProducto("Patatas",array("rojo",100));
$carro->insertarProducto("Queso",array("amarillo",100));
//TODO:
//$carro->insertarPropiedadProducto("Patatas","textura","gruesa");
//$carro->modificarPropiedadProducto("Patatas","textura","gruesa");
//$carro->eliminarPropiedadProducto("Patatas","textura","gruesa");
$carro->mostrarTodosProductos();
echo "Ahora quitamos las papas:<br>";
$carro->borrarProducto("Patatas");
$carro->mostrarTodosProductos();
echo "Fuera todo:<br>";
$carro->vaciar();
$carro->mostrarTodosProductos();

//Guardar todo
$carro ->guardarCarrito();
*/
if (defined('ZF_CESTA_CANTIDAD')) exit(0);
define('ZF_CESTA_CANTIDAD',"cantidad"); //NOMBRE DEL CAMPO DE ZF_CESTA_CANTIDAD
/**
 * zen_carrito
 *
 * Una clase para el carrito de la compra super simple usada para insertar y borrar productos de una session de PHP
 * @package zen_carrito
 * @access public
 */
class zen_carrito {
    /**
    * @desc Nombre de la cookie a usar
    * @var str
    */
    var $nombreCookie = 'carrito';
    /**
    * @desc Donde alojamos nuestros productos
    * @var array
    */
    var $productos;
    /**
    * @desc Cantidad total de productos
    * @var int
    */
    var $total;
    /**
     * zen_carrito::zen_carrito()
     *
     * Constructor. Analiza la cookie si está establecida.
     * @return zen_carrito
     */
    function zen_carrito() {
        if (isset($_SESSION[$this->nombreCookie])) { //Existe el array de productos?
            $this->productos = unserialize(base64_decode($_SESSION[$this->nombreCookie]));
            $this->cuenta_cantidad();
        } else {
            $this->productos = array();
            $this->total     = 0;
        }
    }        
    /**
    * @desc Cuenta la cantidad de $this->productos  y la guarda en $this->total
    */
    function cuenta_cantidad(){
        $this->total = 0;
        foreach($this->productos as $elemento){
            if (isset($elemento[ZF_CESTA_CANTIDAD]))
             $this->total += $elemento[ZF_CESTA_CANTIDAD];
        }
    }
    /**
     * Devuelve si dos array de productos pasados son iguales
     *
     * @param array $p1
     * @param array $p2
     * @param bool $comprobar_cantidad
     * @return boolean
     */
    function productosIguales($p1, $p2,$comprobar_cantidad=false){
        foreach($p1 as $llave => $valor) {
            if (!$comprobar_cantidad && $llave!=ZF_CESTA_CANTIDAD) //Si no es el campo de cantidad
            if ($p2[$llave] != $valor) //son diferentes propiedades?
            return false;
        }
        return true;
    }

    /**
     * Resta en una $cantidad de productos cuya propiedad sea $nombre y tenga de valor $valor
     *
     * @param str $nombre
     * @param variant $valor
     * @return boolean
     */
    function eliminarProductoPorPropiedad($nombre,$valor,$cantidad=1) {
     foreach ($this->productos as $llave => $propiedades) {    
         if (in_array($nombre,array_keys($propiedades)) && in_array($valor,$propiedades)) {
             $c =& $this->productos[$llave][ZF_CESTA_CANTIDAD];
             $c = abs($c - $cantidad);
             $this->total = abs($this->total-$cantidad);
             if ($this->productos[$llave][ZF_CESTA_CANTIDAD]==0) {
             //Si no quedan +productos de ese tipo:
              unset($this->productos[$llave]);
             }
             return true;
         }
     }
     return false;
    }

    /**
     * zen_carrito::eliminar_producto()
     *
     * Quita el producto de la cesta. Si la cantidad es menor que 1 se borra de esta
     * @param mixed $id - Id del producto
     * @param integer $CANTIDAD - Cantidad de productos para borrar de la cesta
     * @see eliminar_todos_producto()
     * @return bool
     */
    function eliminar_producto($id, $CANTIDAD = 1) {

        if (isset($this->productos[$id])) {         
            $this->productos[$id][ZF_CESTA_CANTIDAD] = $this->productos[$id] - $CANTIDAD;
        }

        if ($this->productos[$id] <= 0) {
            $this->eliminar_todos_producto($id);
        } else $this->guardarCarrito();
        
        return true;
        
    }                                         
    
    /**
     * Inserta un producto con el $nombre y $propiedades al array de productos de la clase
     * Si $estricto es true entonces comprobamos si es que hay otro igual para insertarlo 
     * @param str $nombre
     * @param array $propiedades
     * @param boolean $estricto
     */
    function insertarProducto($nombre,$propiedades,$estricto=false){
        if (is_array($propiedades) && (!empty($nombre))){
            //Existe ya el producto en la cesta?
            if (array_key_exists($nombre,$this->productos)){ //Existe?
                //Hay otro producto exactamente igual menos la cantidad?
                if ($estricto) { //Es exactamente el mismo producto,operacion repetida...etc??
                    $nuevo = !($this->productosIguales($this->productos[$nombre],$propiedades));
                } else { //Si no,no es nuevo
                    $nuevo =  false;
                }
            } else $nuevo =true; //no existe en la lista
            foreach($propiedades as $llave => $valor) {
                if ($llave == CANTIDAD) { //ES EL CAMPO CANTIDAD?
                    $this->total += $valor;
                    //Nuevo?
                    if ($nuevo){
                        $this->productos[$nombre][CANTIDAD] = $valor;
                    } else {    //SUMAMOS LA CANTIDAD DE UN PRODUCTO
                        $this->productos[$nombre][CANTIDAD] += $valor;
                    }
                } else //Otro campo, se guarda en el array de productos de la clase
                $this->productos[$nombre][$llave] = $valor;
            } //fin del recorrer las propiedades de un producto a insertar...
        } else {
            
        }
    }

    /**
     * Cambia las propiedades de un producto localizado por su nombre
     *
     * @param str $nombre
     * @param array $propiedades
     */
    function editarProducto($nombre,$propiedades){
        if (is_array($propiedades) && (!empty($nombre)) &&
        array_key_exists($nombre,$this->productos)){
            //Restamos la anterior cantidad
            $this->productos[$nombre][CANTIDAD] -= $propiedades[CANTIDAD];
            foreach($propiedades as $llave => $valor) {
                //Modificamos sus propiedades...
                $this->productos[$nombre][$llave] = $valor;
                //Sumamos la nueva cantidad
                if ($llave==CANTIDAD) $this->total += $valor;
            }

        }
    }
    
    function insertarPropiedadProducto(){
        //TODO
    }
    /**
     * zen_carrito::eliminar_todos_producto($id)
     *
     * Borra todos los productos del tipo $id
     * @param mixed $id
     * @return bool
     */
    function eliminar_todos_producto($id) {
        $this->total -= $this->productos[$id][ZF_CESTA_CANTIDAD];
        unset($this->productos[$id]);
        $this->guardarCarrito();
        return true;
    }

    /**
     * zen_carrito::obtenerproductos()
     *
     * Devuelve todos los contenidos de la cesta
     * @return array
     */
    function &obtenerproductos() {
        return $this->productos;
    }

    /**
     * zen_carrito::actualizar_producto()
     *
     * Updates a basket producto with a specific ZF_CESTA_CANTIDAD
     * @param mixed $id - ID of producto
     * @param mixed $CANTIDAD - ZF_CESTA_CANTIDAD of productos in basket
     * @return bool
     */
    function actualizar_producto($id, $CANTIDAD) {
        $CANTIDAD = ($CANTIDAD<0 || $CANTIDAD == '') ? 0 : $CANTIDAD;
        if (isset($this->productos[$id])) {           
            $c =& $this->productos[$id][ZF_CESTA_CANTIDAD];
            $c = $CANTIDAD;
            if ($c == 0) {
                $this->eliminar_todos_producto($id);
                return true;
                
            }
            $this->guardarCarrito();
            return true;
        } else {
            return false;
        }

    }

    /**
     * zen_carrito::obtenerCantidad()
     *
     * Devuelve la cantidad total de productos de la cesta
     * @return int
     */
    function obtenerCantidad() {
        return $this->total;
    }

    /**
     * zen_carrito::vaciar()
     *
     * Borra la cesta por completo
     * @return bool
     */
    function vaciar() {
        $this->total = 0;
        $this->productos = array();
        $this->guardarCarrito();
        return true;
    }

  /**
   * zen_carrito::guardarCarrito()
   *
   * Almacena los productos
   * @return bool
   */
    function guardarCarrito() {
        $_SESSION[$this->nombreCookie] = base64_encode(serialize($this->productos));            
        return true;
    }
    
    /**
     * Escribe el array de productos de la clase con print_r
     *
     */
    function mostrarTodosProductos(){
        print_r($this->productos);
    }

    /**
     * Elimina un producto del array de la clase y mantiene la cuenta total de estos
     *
     * @param str $nombre
     */
    function borrarProducto($nombre){ //Si el producto existe, lo eliminamos...

        if (array_key_exists($nombre,$this->productos)){
            $this->total -= $this->productos[$nombre][CANTIDAD];
            unset($this->productos[$nombre]);
        }
    }

}

?>