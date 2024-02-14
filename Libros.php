<?php
class Libros
{
    private $conn;

    /**
     * Establece la conexion con el servidor
     * @param mixed $servidor
     * @param mixed $bd
     * @param mixed $usuario
     * @param mixed $pass
     * @return mysqli|null
     */
    public function conexion($servidor, $bd, $usuario, $pass)
    {
        $this->conn = new mysqli($servidor, $usuario, $pass, $bd);
        if ($this->conn->connect_error) {
            $this->conn = null;
            return null;
        }
        return $this->conn;
    }


    /**
     * Consulta los autores existentes
     * @param mixed $conexion
     * @param mixed $id_autor
     * @return array|stdClass|null
     */
    public function consultarAutores($conexion, $id_autor = null)
    {
        if ($id_autor != null) {
            $sql = "Select * from autor where id = $id_autor";

            $result = $conexion->query($sql);
            $array_resultado = $result->fetch_assoc();

            if ($array_resultado) {
                $autor = new stdClass();
                $autor->id = $array_resultado['id'];
                $autor->nombre = $array_resultado['nombre'];
                $autor->apellidos = $array_resultado['apellidos'];
                $autor->nacionalidad = $array_resultado['nacionalidad'];
                return $autor;
            } else {
                return null;
            }
        } else {
            $sql = "Select * from autor";
            $result = $conexion->query($sql);
            //Comprobar si devolver como array de objetos
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            return $data;
        }
    }

    /**
     * Consulta los libros existentes
     * @param mixed $conexion
     * @param mixed $id_autor
     * @return array
     */
    public function consultarLibros($conexion, $id_autor = null)
    {
        if ($id_autor != null) {
            $sql = "Select * from libro where id_autor = $id_autor";
            $result = $conexion->query($sql);
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $fecha = new DateTime($row['f_publicacion']);
                $obj = new stdClass();
                $obj->id = $row['id'];
                $obj->titulo = $row['titulo'];
                $obj->f_publicacion = $fecha->format('d/m/Y');
                $obj->id_autor = $id_autor;
                $data[] = $obj;
            }

            return  $data;
        } else {
            $sql = "Select * from libro";
            $result = $conexion->query($sql);
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    /**
     * Consulta los datos de un libro solicitado
     * @param mixed $conexion
     * @param mixed $id_libro
     * @return stdClass|null
     */
    public function consultarDatosLibro($conexion, $id_libro = null)
    {
        $sql = "Select * from libro where id = $id_libro";
        $result = $conexion->query($sql);
        $result_array = $result->fetch_assoc();
        if ($result_array) {
            $fecha = new DateTime($result_array['f_publicacion']);
            $obj = new stdClass();
            $obj->id = $result_array['id'];
            $obj->titulo = $result_array['titulo'];
            $obj->f_publicacion = $fecha->format('d/m/Y');
            $obj->id_autor = $result_array['id_autor'];
            return $obj;
        } else {
            return null;
        }
    }

    /**
     * Elimina un autor a partir de su id
     * @param mixed $conexion
     * @param mixed $id_autor
     * @return bool
     */
    public function borrarAutor($conexion, $id_autor)
    {
        $sql = "Delete from autor where id = $id_autor";
        if ($conexion->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Elimina un libro a partir de su id
     * @param mixed $conexion
     * @param mixed $id_libro
     * @return boolean
     */
    public function borrarLibro($conexion, $id_libro)
    {
        $sql = "Delete from libro where id = $id_libro";
        if ($conexion->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Obtiene el listado de libros por el autor dado.
     * @param mixed $conexion
     * @param mixed $id_autor
     * @return array
     */
    public function librosAutor($conexion, $id_autor)
    {
        $sql = "select l.id, l.titulo,l.f_publicacion, l.id_autor 
         from autor a inner join libro l on l.id_autor = a.id 
          where a.id=$id_autor";
        $result = $conexion->query($sql);
        $data = [];
            while ($row = $result->fetch_assoc()) {
                $fecha = new DateTime($row['f_publicacion']);
                $obj = new stdClass();
                $obj->id = $row['id'];
                $obj->titulo = $row['titulo'];
                $obj->f_publicacion = $fecha->format('d/m/Y');
                $obj->id_autor = $id_autor;
                $data[] = $obj;
            }
        return $data;

    }
}

