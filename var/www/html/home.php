<?php

//Comprobamos que la sesión esté iniciada
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel de control</title>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    background: #ffffff;
}

.wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    width: 100%;
    max-width: 600px;
    border: 1px solid #ccc;
    padding: 20px;
    background: #f9f9f9;
}

h2 {
    margin-top: 0;
}

.menu {
    margin-top: 15px;
}

.item {
    display: block;
    padding: 12px 14px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    background: #f5f5f5;
    color: #333;
    text-decoration: none;
}

.item:hover {
    background: #eeeeee;
}

.item small {
    display: block;
    margin-top: 4px;
    color: #666;
}
</style>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <h2>Bienvenido, <?= htmlspecialchars($_SESSION['user']) ?></h2>

        <div class="menu">
            <a class="item" href="logs.php">
                Logs de ataques
            </a>

            <a class="item" href="logs_agrupados.php">
                Ataques agrupados
            </a>

            <a class="item" href="grafica.php">
                Gráficas
            </a>

            <a class="item" href="repositorio_pcap.php">
                Repositorio PCAP
            </a>

            <a class="item" href="logout.php">
                Cerrar sesión
            </a>
        </div>
    </div>
</div>
</body>
</html>
