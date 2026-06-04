<?php
require_once "db.php";

//Comenzamos analizando los parámetros que ofrece PHP
//En este caso añadimos como variable la IP
$ip     = $_SERVER['REMOTE_ADDR'] ?? 'desconocido';

//Aquí capturamos la URL

$url    = $_SERVER['REQUEST_URI'] ?? '';

//Aquí capturamos el agente.

$agente = $_SERVER['HTTP_USER_AGENT'] ?? '';

// Añadimos la variable ataque por defecto a posible ataque, así lo que no podamos filtrar, lo dejamos como posible ataque.

$ataque = "Posible ataque";

// Cogemos las variables de url y de agente y las pasamos a minuscula. La de url se decodifica para quitar los símbolos y poder analizarla mejor
$url_l = urldecode(strtolower($url));
$agente_l = strtolower($agente);

// Añadimos los filtros configurables para la detección de ataques, para ello utilizamos la función preg_match, para ver si contiene alguna de las cadenas.

// Añadimos la lógica para capturar todo lo que puedan ser posibles ataques mediante inyección de SQL
if (preg_match('/(\bunion\b|\bselect\b|\bdrop\b|\balter\b|\bupdate\b-|\binsert\b|\grant\b|--)/', $url_l)) {
    $ataque = "Inyección SQL";
}

// Se añade la lógica para detectar ataques XSS
elseif (preg_match('/(<script>|javascript:|onerror=|onload=)/', $url_l)) {
    $ataque = "XSS";
}

// Se añade la logica para el path traversal
elseif (preg_match('/(\.\.\/|\.\.\\\\)/', $url_l)) {
    $ataque = "Path Traversal";
}

// Se añade la lógica para los bytes nulos
elseif (preg_match('/(%00|\x00)/', $url_l)) {
    $ataque = "Inyección de bytes nulos";
}

// Se añade la lógica para la inyección de comandos
elseif (preg_match('/(;|\||&&|wget|curl|chmod|chown)/', $url_l)) {
    $ataque = "Inyección de comandos";
}

// Aquí se identificarían los intentos de ataque a paneles habituales y ficheros.
elseif (preg_match('/(wp-|phpmyadmin|\.env|\.git|setup\.php|info\.php)/', $url_l)) {
    $ataque = "Análisis de paneles/ficheros";
}

// En este caso se analiza el agente y no la URL, aquí se intentan identificar los bots.
elseif (preg_match('/(sqlmap|nikto|acunetix|nmap|curl|python|fuzzer|scan|bot|robot|wget)/', $agente_l)) {
    $ataque = "Scanner/Bot";
}

// Aquí se identifican los intentos mediante fuerza bruta
elseif (preg_match('/(login|password|admin)/', $url_l)) {
    $ataque = "Fuerza bruta";
}

//En este caso hemos añadido todas las direcciones que tiene nuestra página, de esta forma los tratamos como visita controlada.

elseif (preg_match('/(^\/$|index.php|logs.php|logs_agrupados.php|home.php|favicon.ico|repositorio_pcap.php|grafica.php|download.php)/', $url_l)) {
    $ataque = "Visita controlada";
}

// Aunque ya le dieramos en un comienzo el valor de Posible ataque añadimos el else por si acaso.
else {
    $ataque = "Posible ataque";
}

//  Insertamos en la base de datos los cuatro valores que hemos capturado.

$stmt = $conexionsql->prepare("INSERT INTO attack_logs (ip, url, agente, ataque) VALUES (?, ?, ?, ?)");

//Añadimos los cuatro valores como string

$stmt->bind_param("ssss", $ip, $url, $agente, $ataque);
$stmt->execute();
?>
