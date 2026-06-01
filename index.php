<?php

//Preparamos la conexión con la base de datos para hacer login
session_start();
require_once "db.php";

//Si se hace un post con usuarios y contraseña
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $_POST['user'];
    $pass = $_POST['pass'];

//Realizamos la select para consultar el id y el password

    $consulta = "SELECT id, password FROM usuarios WHERE username = ?";
    $stmt = $conexionsql->prepare($consulta);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->bind_result($id, $passsha256);

//Si la consulta devuelve resultados se compara que el password indicado sea el correcto. Está codificado en sha256. 
//Y una vez se confirma la conexión, se redirige al home.php. Sino se guarda en la variable error que se mostrará más abajo
    if ($stmt->fetch()) {
        if (hash("sha256", $pass) === $passsha256) {
            $_SESSION['user'] = $user;
            header("Location: home.php");
            exit;
        } else {$error = "Contraseña incorrecta";}
    } else {$error = "Usuario no encontrado";}
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Acceso al sistema</title>
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
    max-width: 400px;
    border: 1px solid #ccc;
    padding: 20px;
    background: #f9f9f9;
}

h2 {
    margin-top: 0;
}

label {
    display: block;
    margin-top: 10px;
}

input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
    margin-top: 4px;
}

button {
    margin-top: 15px;
    padding: 8px 14px;
    background: #f5f5f5;
    border: 1px solid #ccc;
    cursor: pointer;
}

button:hover {
    background: #eeeeee;
}

.error {
    margin-top: 10px;
    color: red;
}
</style>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <h2>Acceso a la aplicación</h2>

        <form method="post">
            <label>Usuario</label>
            <input type="text" name="user" required>

            <label>Contraseña</label>
            <input type="password" name="pass" required>

            <button type="submit">Entrar</button>
        </form>

        <?php if (!empty($error)): ?>
            <div class="error"><?= ($error) ?></div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
