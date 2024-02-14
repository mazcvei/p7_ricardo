<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API DWES: Tarea 7</title>

<body>

    <?php
    // Si se ha hecho una peticion que busca informacion de un autor "get_datos_autor" a traves de su "id"...
    if (isset($_GET["action"]) && isset($_GET["id"]) && $_GET["action"] == "get_datos_autor") {
        //Se realiza la peticion a la api que nos devuelve el JSON con la información de los autores
        $app_info = file_get_contents('http://localhost/p7_ricardo/api.php?action=get_datos_autor&id=' . $_GET["id"]);
        // Se decodifica el fichero JSON y se convierte a array
        $app_info = json_decode($app_info);

    ?>
        <p>
            <td>Nombre: </td>
            <td> <?php echo $app_info->datos->nombre ?></td>
        </p>
        <p>
            <td>Apellidos: </td>
            <td> <?php echo $app_info->datos->apellidos ?></td>
        </p>
        <p>
            <td>Nacionalidad: </td>
            <td> <?php echo $app_info->datos->nacionalidad ?></td>
        </p>
        <ul>
            <!-- Mostramos los libros del autor -->
            <?php foreach ($app_info->libros as $libro) : ?>
                <li>
                    <?php echo $libro->titulo; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <br />
          
    <?php
       echo "<br><br><a href='cliente.php'>Volver a listado de libros</a>";
    } //Cierra if get_datos_autor
    else if (isset($_GET["action"]) && isset($_GET["id"]) && $_GET["action"] == "get_datos_libro") {
        $app_info = file_get_contents("http://localhost/p7_ricardo/api.php?action=get_datos_libro&id=" . $_GET['id']);
        // Se decodifica el fichero JSON y se convierte a array
        $datos = json_decode($app_info);
        echo "<h1>Datos del libro: </h1>";
        echo "<br>Titulo:" . $datos->libro->titulo;
        echo "<br>Fecha publicacion:" . $datos->libro->f_publicacion;
        echo "<br>Autor: <a href='cliente.php?action=get_datos_autor&id=" . $datos->libro->id_autor . "'>Ver datos autor</a>";
        echo "<br><br><a href='cliente.php'>Volver a listado de libros</a>";
    } else {
        //Cierra if get_datos_libro



        //Se realiza la peticion a la api que nos devuelve el JSON con la información de los autores
        $app_info_libros = file_get_contents('http://localhost/p7_ricardo/api.php?action=get_listado_libros');

        // Se decodifica el fichero JSON y se convierte a array
        $libros = json_decode($app_info_libros);

        $app_info_autores = file_get_contents('http://localhost/p7_ricardo/api.php?action=get_listado_autores');
     

        // Se decodifica el fichero JSON y se convierte a array
        $autores = json_decode($app_info_autores);
       
        echo "<h1>Listado de libros:</h1>";
        foreach ($libros as $libro) {
            echo " <li>
             $libro->titulo; 
             <a href='cliente.php?action=get_datos_libro&id=$libro->id'>Ver datos libro</a>
        </li> ";
        }

        echo "<h1>Listado de autores:</h1>";
        
        foreach ($autores as $autor) {
            echo " <li>
             $autor->nombre 
             <a href='cliente.php?action=get_datos_autor&id=$autor->id'>Ver datos autor</a>
        </li> ";
        }
    }

    ?>