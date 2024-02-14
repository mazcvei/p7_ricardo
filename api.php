<?php
require("Libros.php");
// Esta API tiene dos posibilidades; Mostrar una lista de autores o mostrar la información de un autor específico.

$servidor = 'localhost';
$usuario = 'root';
$contrasena = '';
$basedatos = 'foc3';

function get_datos_autor($id) {
    global $servidor, $usuario, $contrasena, $basedatos;
    $libros = new Libros();
    $con = $libros->conexion($servidor,$basedatos,$usuario,$contrasena);
    $datos_autor = new stdClass();
    // consultarAutores devuelve un objeto
    $datos_autor->datos = $libros->consultarAutores($con,$id); 
    // devuelve una array de objetos de tipo libro
    $datos_autor->libros = $libros->consultarLibros($con,$id);
    return $datos_autor;
}

function get_listado_libros() {
    global $servidor, $usuario, $contrasena, $basedatos;
    $libros = new Libros();
    $con = $libros->conexion($servidor,$basedatos,$usuario,$contrasena);
    $libros = $libros->consultarLibros($con, null);
    $array_datos_libros = array();
    
    foreach ($libros as $libro) {
        $libro_id_titulo = array(
            'id' => $libro['id'],
            'titulo' => $libro['titulo'],
        );
        $array_datos_libros[] = $libro_id_titulo;
    }
    return $array_datos_libros;
}

function get_datos_libro($id) {
    global $servidor, $usuario, $contrasena, $basedatos;
    $libros = new Libros();
    $con = $libros->conexion($servidor,$basedatos,$usuario,$contrasena);
    $datos = new StdClass();
    $datos->libro = $libros->consultarDatosLibro($con, $id);;
    return $datos;
}

function get_listado_autores() {
    global $servidor, $usuario, $contrasena, $basedatos;
    $libros = new Libros();
    $con = $libros->conexion($servidor,$basedatos,$usuario,$contrasena);
    $autores = $libros->consultarAutores($con, null);
    $array_datos_autores = array();
    
    foreach ($autores as $autor) {
        $array_datos_autores[] = array(
            'id' => $autor['id'],
            'nombre' => $autor['nombre'],
            'apellidos' => $autor['apellidos'],
            'nacionalidad' => $autor['nacionalidad'],
        );
      
    }
	
    return $array_datos_autores;
}
$posibles_URL = array( "get_datos_autor", "get_listado_libros","get_listado_autores","get_datos_libro");

$valor = "Ha ocurrido un error";

if (isset($_GET["action"]) && in_array($_GET["action"], $posibles_URL))
{
  switch ($_GET["action"]) {
    case "get_datos_autor":
        if (isset($_GET["id"]))
            $valor = get_datos_autor($_GET["id"]);
        else
            $valor = "Argumento no encontrado";
        break;
     case "get_listado_libros":
           
        $valor = get_listado_libros();
        break;
    case "get_listado_autores":
        
            $valor = get_listado_autores();
            break;

    case "get_datos_libro":
            if (isset($_GET["id"]))
            $valor = get_datos_libro($_GET["id"]);
            else
            $valor = "Argumento no encontrado";
        
        break;
    }

}

//devolvemos los datos serializados en JSON
exit(json_encode($valor));
?>
