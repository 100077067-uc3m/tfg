<?php

//Comprobamos que la sesión este iniciada
session_start();
if (!isset($_SESSION['user'])) {
header("Location: index.php");
exit;
}

//Conectamos con la base de datos
require_once "db.php";

//Hacemos la query y la metemos en la variable

$consulta = "SELECT * FROM attack_logs ORDER BY fecha DESC";

//Hacemos la select  para mostrar todos los ataques. 
$resultado = $conexionsql->query($consulta);

//Si no devuelve nada, mostramos el error
if (!$resultado) {die("Error en la consulta: " . $conexionsql->error);}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Logs de ataques</title>
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
table { border-collapse: collapse; width: 100%; }
th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
th { background: #f5f5f5; }
td.url { max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.gohome {position: absolute;right: 20px;top: 10px;padding: 8px 15px;background-color: #ccc;color: black;text-decoration: none;border-radius: 5px;margin-bottom: 15px;}
</style>
</head>
<body>
<h2>Logs de ataques</h2>
<a href="home.php" class="gohome">Volver al inicio</a>
<table>
<thead>
<tr>
<th>ID</th>
<th>IP</th>
<th>URL</th>
<th>Agente</th>
<th>Ataque</th>
<th>Fecha</th>
</tr>
</thead>
<tbody>

<!--Aquí mostramos los datos de la select, recorriendolo con un while.-->
<?php while ($fila = $resultado->fetch_assoc()): ?>
<tr>
<td><?= $fila['id'] ?></td>
<td><?= $fila['ip'] ?></td>
<td class="url"><?= htmlspecialchars($fila['url']) ?></td>
<td><?= htmlspecialchars($fila['agente']) ?></td>
<td><?= $fila['ataque'] ?></td>
<td><?= $fila['fecha'] ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</body>
</html>
