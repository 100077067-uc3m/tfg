<?php

//Aquí hacemos el cierre de la sesión.
session_start();
session_destroy();
header("Location: index.php");
?>
