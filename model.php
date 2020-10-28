<?php
//Libreria de interacciones

function conectar() 
{

    $conexion_bd = mysqli_connect("exa-db-a01275595-do-user-8217587-0.b.db.ondigitalocean.com","doadmin","ysuucn7048n2le7u","Exa2Parcial", "25060");
    
    if ($conexion_bd == NULL) 
    {
        die("No se pudo conectar a la base de datos");
    }
    
    $conexion_bd->set_charset("utf8");
    
    return $conexion_bd;
}

function desconectar($conexion_bd) 
{
    mysqli_close($conexion_bd);
}

function tablaClientes() 
{
    $consulta = 'SELECT * ';
    $consulta .= 'FROM CLIENTE';
    
    $conexion_bd = conectar();
    $resultados_consulta = $conexion_bd->query($consulta);  
    
    $resultado = '<table class="table">';
    $resultado .= '<thead><tr><th scope="col">IdCliente</th><th scope="col">Nombre</th><th scope="col">Apellido</th><th scope="col">Dirección</th><th scope="col">Población</th><th scope="col">Código Postal</th><th scope="col">Teléfono</th><th scope="col">Fecha de Nacimiento</th></tr></thead>';

    while ($row = mysqli_fetch_array($resultados_consulta, MYSQLI_ASSOC)) 
    {
        $resultado .= '<tbody>';    
        $resultado .= '<tr>';
        $resultado .= '<th scope="row">'.$row["idCliente"].'</th>';
        $resultado .= '<td>'.$row["nombreCliente"].'</td>';
        $resultado .= '<td>'.$row["apellidosCliente"].'</td>';
        $resultado .= '<td>'.$row["direccionCliente"].'</td>';
        $resultado .= '<td>'.$row["poblacion"].'</td>';
        $resultado .= '<td>'.$row["codigoPostal"].'</td>';
        $resultado .= '<td>'.$row["telefono"].'</td>';
        $resultado .= '<td>'.$row["fechaNac"].'</td>';
        $resultado .= '</tr>';
        $resultado .= '</tbody>';
    }
    
    mysqli_free_result($resultados_consulta); //Liberar la memoria
    
    $resultado .= '</table>';
    
    desconectar($conexion_bd);
    return $resultado;
}

function tablaCocheVenta() 
{
    $consulta = 'SELECT * ';
    $consulta .= 'FROM COCHE_VENDIDO';
    
    $conexion_bd = conectar();
    $resultados_consulta = $conexion_bd->query($consulta);  
    
    $resultado = '<table class="table">';
    $resultado .= '<thead><tr><th scope="row">Matricula</th><th scope="row">Marca</th><th scope="row">Modelo</th><th scope="row">Color</th><th scope="row">Precio</th><th scope="row">ExtrasInstalados</th><th scope="row">Cliente</th></tr></thead>';

    while ($row = mysqli_fetch_array($resultados_consulta, MYSQLI_ASSOC)) 
    { 
    //MYSQLI_NUM: Devuelve los resultados en un arreglo numérico
        //$row[0]
    //MYSQLI_ASSOC: Devuelve los resultados en un arreglo asociativo
        //$row["acusador"]
    //MYSQL_BOTH: Devuelve los resultados en un arreglo numérico y asociativo (Utiliza el doble de memoria)
        //$row[0] y $row["acusador"]
        
        $resultado .= '<tbody>';
        $resultado .= '<tr>';
        $resultado .= '<th scope="row">'.$row["matricula"].'</th>';
        $resultado .= '<td>'.$row["marca"].'</td>';
        $resultado .= '<td>'.$row["modelo"].'</td>';
        $resultado .= '<td>'.$row["color"].'</td>';
        $resultado .= '<td>'.$row["precio"].'</td>';
        $resultado .= '<td>'.$row["extrasInstalados"].'</td>';
        $resultado .= '<td>'.$row["cliente"].'</td>';
        $resultado .= '</tr>';
        $resultado .= '</tbody>';
    }
    
    mysqli_free_result($resultados_consulta); //Liberar la memoria
    
    $resultado .= '</table>';
    
    desconectar($conexion_bd);
    return $resultado;
}

function tablaRevision()
{
    $consulta = 'SELECT * ';
    $consulta .= 'FROM REVISION';
    
    $conexion_bd = conectar();
    $resultados_consulta = $conexion_bd->query($consulta);  
    
    $resultado = '<table class="table">';
    $resultado .= '<thead><tr><th scope="col">Numero</th><th scope="col">Cambio de Aceite</th><th scope="col">Cambio de Filtro</th><th scope="col">Revisión de Frenos</th><th scope="col">Otros</th><th scope="col">Matrícula</th></tr></thead>';

    while ($row = mysqli_fetch_array($resultados_consulta, MYSQLI_ASSOC)) 
    {   
        $resultado .= '<tbody>';     
        $resultado .= '<tr>';
        $resultado .= '<th scope="row">'.$row["noRevision"].'</th>';
        $resultado .= '<td>'.$row["cambioAceite"].'</td>';
        $resultado .= '<td>'.$row["cambioFiltro"].'</td>';
        $resultado .= '<td>'.$row["revisionFrenos"].'</td>';
        $resultado .= '<td>'.$row["otros"].'</td>';
        $resultado .= '<td>'.$row["matricula"].'</td>';
        $resultado .= '</tr>';
        $resultado .= '</tbody>';
    }
    
    mysqli_free_result($resultados_consulta);
    
    $resultado .= '</table>';
    
    desconectar($conexion_bd);
    return $resultado;
}

function select($name, $tabla, $id="id", $nombre="nombre") 
{
    $resultado = '<select name="'.$name.'" class="browser-default">';
    $resultado .= '<option value="" disabled selected>Selecciona un '.$tabla.'</option>';
    $conexion_bd = conectar();
    
    $consulta = 'SELECT '.$id.', '.$nombre.' FROM '.$tabla.' ORDER BY '.$nombre.' ASC'; //SELECT idCliente, nombreCliente FROM CLIENTE ORDER BY nombreCliente 
    $resultados_consulta = $conexion_bd->query($consulta);  
    
    while ($row = mysqli_fetch_array($resultados_consulta, MYSQLI_BOTH)) 
    {
        
        $resultado .= '<option value="'.$row[$id].'">'.$row[$nombre].'</option>';
    }
    
    mysqli_free_result($resultados_consulta); //Liberar la memoria
    
    $resultado .= '</select><label>'.$tabla.'</label>';
    
    desconectar($conexion_bd);
    return $resultado;
}
//echo select("cliente", "cliente", "idCliente", "nombreCliente");


function updateColorAuto($matricula, $nuevoColor = 'Negro') 
{
     
    $conexion_bd = conectar();
    
    $consulta = "UPDATE coche_vendido SET color = ? WHERE matricula = ?";
    //Parametros son los signos de interrogación


    //Verifica que la consulta sea correcta
    if(!($statement = $conexion_bd->prepare($consulta))) 
    {
        die("Error(".$conexion_bd->errno."): ".$conexion_bd->error);
    }
    
    //Evita +- SQL inyection | Hace la union entre los parametros con las cosultas/Sustituye ??s por datos
    if(!($statement->bind_param("ss",$nuevoColor, $matricula))) 
    {
        die("Error de vinculación(".$statement->errno."): ".$statement->error);
    }
    
    if(!$statement->execute()) 
    {
        die("Error en ejecución de la consulta(".$statement->errno."): ".$statement->error);
    }
    
    desconectar($conexion_bd);
}
//updateColorAuto('V8018LJ', 'Verde');
//echo tablaCocheVenta();

function InsertNuevoCliente($idCliente, $nombreCliente, $apellidosCliente, $direccionCliente, $poblacion, $codigoPostal, $telefono, $fechaNac) 
{
     
    $conexion_bd = conectar();
    
    $consulta = 'INSERT INTO CLIENTE VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
    //Parametros son los signos de interrogación

    //Verifica que la consulta sea correcta
    if(!($statement = $conexion_bd->prepare($consulta))) 
    {
        die("Error(".$conexion_bd->errno."): ".$conexion_bd->error);
    }
    
    //Evita +- SQL inyection | Hace la union entre los parametros con las cosultas/Sustituye ?s por datos
    if(!($statement->bind_param("ssssssss", $idCliente, $nombreCliente, $apellidosCliente, $direccionCliente, $poblacion, $codigoPostal, $telefono, $fechaNac))) 
    {
        die("Error de vinculación(".$statement->errno."): ".$statement->error);
    }
    
    if(!$statement->execute()) 
    {
        die("Error en ejecución de la consulta(".$statement->errno."): ".$statement->error);
    }
    
    desconectar($conexion_bd);
}

function EliminarCliente($idCliente) 
{
     
    $conexion_bd = conectar();
    
    $consulta = "DELETE FROM CLIENTE WHERE idCliente = ?";
    //Parametros son los signos de interrogación

    //Verifica que la consulta sea correcta
    if(!($statement = $conexion_bd->prepare($consulta))) 
    {
        die("Error(".$conexion_bd->errno."): ".$conexion_bd->error);
    }
    
    //Evita +- SQL inyection | Hace la union entre los parametros con las cosultas/Sustituye ?s por datos
    if(!($statement->bind_param("s", $idCliente))) 
    {
        die("Error de vinculación(".$statement->errno."): ".$statement->error);
    }
    
    if(!$statement->execute()) 
    {
        die("Error en ejecución de la consulta(".$statement->errno."): ".$statement->error);
    }
    
    desconectar($conexion_bd);
}

function ActualizarCliente($idCliente, $direccionCliente, $poblacion, $codigoPostal, $telefono)
{
    $conexion_bd = conectar();
    
    $consulta = "UPDATE CLIENTE SET direccionCliente = ?, poblacion = ?, codigoPostal = ?, telefono = ? WHERE idCliente = ?";
    //Parametros son los signos de interrogación

    //Verifica que la consulta sea correcta
    if(!($statement = $conexion_bd->prepare($consulta))) 
    {
        die("Error(".$conexion_bd->errno."): ".$conexion_bd->error);
    }
    
    //Evita +- SQL inyection | Hace la union entre los parametros con las cosultas/Sustituye ??s por datos
    if(!($statement->bind_param("sssss", $direccionCliente, $poblacion, $codigoPostal, $telefono, $idCliente))) 
    {
        die("Error de vinculación(".$statement->errno."): ".$statement->error);
    }
    
    if(!$statement->execute()) 
    {
        die("Error en ejecución de la consulta(".$statement->errno."): ".$statement->error);
    }
    
    desconectar($conexion_bd);
}
?>