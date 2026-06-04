<?php

// Se hace una select para mostrar los ataques agrupados.

//Cogemos las variables de db.php
require_once "db.php";


// Hacemos el select
$consulta = "SELECT ataque, COUNT(*) AS total, MIN(fecha) AS primera_vez, MAX(fecha) AS ultima_vez FROM attack_logs GROUP BY ataque ORDER BY total DESC";

// Y lanzamos la consulta al SQL
$resultado = $conexionsql->query($consulta);

//Si falla, mostramos el error de la consulta
if (!$resultado) {die("Error en la consulta: " . $conexionsql->error);}

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Resumen de ataques</title>
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
table { border-collapse: collapse; width: 100%; }
th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
th { background: #f2f2f2; }
.gohome {position: absolute;right: 20px;top: 10px;padding: 8px 15px;background-color: #ccc;color: black;text-decoration: none;border-radius: 5px;margin-bottom: 15px;}
</style>
</head>
<body>
<h2>Resumen de ataques registrados</h2>
<a href="home.php" class="gohome">Volver al inicio</a>
<table>
<thead>
<tr>
<th>Tipo de ataque</th>
<th>Total</th>
<th>Primera vez</th>
<th>Última vez</th>
</tr>
</thead>
<tbody>
<?php while ($fila = $resultado->fetch_assoc()): ?>
<tr>
<td><?= $fila['ataque'] ?></td>
<td><?= $fila['total'] ?></td>
<td><?= $fila['primera_vez'] ?></td>
<td><?= $fila['ultima_vez'] ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</body>
</html>
