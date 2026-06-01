<?php
//Vemos si la sesión sigue activa, sino redirige al index.php
session_start();
if (!isset($_SESSION['user'])) {
header("Location: index.php");
exit;
}
//Capturamos las variables de la base de datos
require_once "db.php";
//Hacemos la select para calcular los porcentajes, redondeamos a 2 dígitos para que sea más visible.
$consulta = "SELECT ataque,COUNT(*) AS total,ROUND((COUNT(*) * 100.0 /(SELECT COUNT(*) FROM attack_logs)),2) AS porcentaje 
FROM attack_logs GROUP BY ataque ORDER BY porcentaje desc";

//ejecutamos la consulta

$resultado = $conexionsql->query($consulta);

//Se crean 2 arrays para cargarlos con los datos de la query
$valores = [];
$total   = [];

while ($fila = $resultado->fetch_assoc()) {
    $valores[] = $fila["ataque"]."(".$fila["porcentaje"]."%)";
    $total[] = $fila["total"];
}
//se trasforma el array en json para que se pueda utilizar en javascript
$valores_json = json_encode($valores);
$total_json   = json_encode($total);
?>
<!--Se utiliza esta web para crear la gráfica https://www.chartjs.org/docs/latest/charts/bar.html-->
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Gráfica de ataques</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body {font-family: Arial, sans-serif;padding: 20px;text-align: center;}
.chart-container {width: 80%;max-width: 700px;margin: auto;}
.gohome {position: absolute;right: 20px;top: 10px;padding: 8px 15px;background-color: #ccc;color: black;text-decoration: none;border-radius: 5px;margin-bottom: 15px;}
</style>
</head>
<body>
<h2>Estadísticas de ataques</h2>
<a href="home.php" class="gohome">Volver al inicio</a>
<div class="chart-container">
    <canvas id="graficaLogsAtaques"></canvas>
</div>
<script>
const valores = <?php echo $valores_json; ?>;
const total   = <?php echo $total_json; ?>;
new Chart(document.getElementById('graficaLogsAtaques'), {
    type: 'bar',
    data: {
        labels: valores,
        datasets: [{
            label: 'Número de ataques',
            data: total,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: { display: false }
        }
    }
});
</script>
</body>
</html>
