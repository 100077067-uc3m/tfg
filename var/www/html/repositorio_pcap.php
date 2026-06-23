<?php

//Comprobamos que el usuario tenga inidicada la sesión
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

//Declaramos la variable totalficheros y le pasamos la ruta donde están guardados los logs, la funcion glob coge los ficheros que tengan ese patrón

$totalficheros = glob('/var/log/pcap' . '/*.pcap');

//Después ordenamos con rsort de manera descendente
//rsort($totalficheros);


usort($totalficheros, function($a, $b) {

    preg_match('/_(\d{14})\.pcap$/', basename($a), $fechaA);
    preg_match('/_(\d{14})\.pcap$/', basename($b), $fechaB);

    return strcmp($fechaB[1], $fechaA[1]);

});

//Creamos la función siguiente para poner el formato 20260513172648 del nombre del fichero a formato fecha 2026-05-13 17:26:48

function formatoFechaPcap($fechasinformato) {
    $fechaconformato = DateTime::createFromFormat('YmdHis', $fechasinformato);
    return $fechaconformato->format('Y-m-d H:i:s');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Repositorio PCAP</title>
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
table { border-collapse: collapse; width: 100%; }
th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
th { background: #f5f5f5; }
td.filename { max-width: 400px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
a { color: #0066cc; text-decoration: none; }
a:hover { text-decoration: underline; }
.gohome {position: absolute;right: 20px;top: 10px;padding: 8px 15px;background-color: #ccc;color: black;text-decoration: none;border-radius: 5px;margin-bottom: 15px;}
</style>
</head>
<body>

<h2>Repositorio de capturas PCAP</h2>
<a href="home.php" class="gohome">Volver al inicio</a>
<table>
<thead>
<tr>
    <th>ID</th>
    <th>Fecha</th>
    <th>Fichero</th>
    <th>Tamaño</th>
    <th>Descarga</th>
</tr>
</thead>
<tbody>

<?php

//Recorremos todos los ficheros para sacar el nombre, el tamaño, la 
$contador = 1;
foreach ($totalficheros as $fichero):

//Sacamos el nombre del fichero
    $nombre = basename($fichero);
//Sacamos el tamaño del fichero en MB que es mucho más legible
    $tamano = round(filesize($fichero) / 1024 / 1024, 2) . " MB";

//En este punto sacamos del nombre del fichero 14 números y guardamos estos números sin formato en fechasinsignos.
    preg_match('/_(\d{14})\.pcap$/', $nombre, $fecha);
    $fechasinsignos = $fecha[1];
?>
<tr>
    <td><?= $contador++ ?></td>
    <td><?= htmlspecialchars(formatoFechaPcap($fechasinsignos)) ?></td>
    <td class="filename"><?= htmlspecialchars($nombre) ?></td>
    <td><?= $tamano ?></td>
    <td><a href="download.php?fichero=<?= urlencode(nombre) ?>">Descargar</a></td>
</tr>
<?php endforeach; ?>

</tbody>
</table>
</body>
</html>
