<?php

//añadimos la linea habitual para comprobar que está iniciada la sesión
session_start();
if (!isset($_SESSION['user'])) {header("Location: index.php");
    exit;
}
//Aquí creamos la variable con el path donde se encuentran los ficheros
$directorio = realpath('/var/log/pcap');

//esta variable captura el valor de la variable fichero que llega del link que enviamos desde el php de PCAP. Si no llega nada se devuelve vacío.
$nombrefichero = $_GET['fichero'] ?? '';

// Controlamos que los ficheros accesibles únicamente sean los que empiezan por ataques_fechayhora.pcap, sino se devuelve error
if (!preg_match('/^ataques_\d{5}_\d{14}\.pcap$/', $nombrefichero)) {
    http_response_code(400);
    exit('Fichero no válido');
}

//Juntamos la ruta del directorio y le añadimos el nombre del fichero.

$rutafinal = $directorio . "/" . $nombrefichero;

//Añadimos las cabeceras para que el navegador identitique que es un fichero pcap con su tamaño
header('Content-Type: application/vnd.tcpdump.pcap');
header('Content-Disposition: attachment; filename="' . $nombrefichero . '"');
header('Content-Length: ' . filesize($rutafinal));

readfile($rutafinal);
exit;
