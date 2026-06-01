<?php

//Se crea la conexión a la base de datos
$servidor = "localhost";
$user = "dani";
$pass = "Dani1989????";
$dataset = "analisistfg";

//Creamos la variable de conexión para luego poder conectarnos desde los distintos phps
$conexionsql = new mysqli($servidor, $user, $pass, $dataset);

//Se controla el error
if ($conexionsql->connect_error) {
    die("Error de conexión: " . $conexionsql->connect_error);
}
?>
