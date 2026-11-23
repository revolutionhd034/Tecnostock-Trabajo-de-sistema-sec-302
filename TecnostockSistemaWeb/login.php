<?php
require_once"conexion.php";
$mensaje="";
function login(PDO $pdo)
{
    $correo=$_POST["correo"];
    $contraseña=$_POST["contraseña"];

    $correo=trim($correo);
    $contraseña=trim($contraseña);

    if($correo==="" || $contraseña==="")
    {
        return "Complete los Campos";
    }

    $consulta=$pdo->prepare("
    SELECT * FROM usuarios WHERE correo = ?
    ");

    $consulta->execute([$correo]);

    $usuario=$consulta->fetch();

    if($usuario)
    {
        if($usuario["contraseña"]===$contraseña)
        {
            header("Location:PaginaPrincipal.html");
            exit;
        }
        else
        {
            return "Credenciales incorrectas";
        }
    }
    else
    {
        return "Credenciales incorrectas";
    }
}
if($_SERVER["REQUEST_METHOD"]==="POST")
{
    $mensaje=login($pdo);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Inicio De Sesión</title>
</head>
<body style=" display: flex; justify-content: center;">

    <form style="margin: 5%; width: 30%;text-align: center;" action="login.php" method="POST" class="form">
        <h1 class="container">Inicio Sesión</h1>
     <label for="correo"class="form-label ">Usuario</label>
     <input type="email" placeholder="Ingrese su Correo" class="form-control" id="correo" name="correo">
      <label for="contraseña"class="form-label">Contraseña</label>
     <input type="password" placeholder="Ingrese su Contraseña" class="form-control" id="contraseña" name="contraseña">
     <button type="submit" style="margin: 1%;">Iniciar Sesión</button>
     <p class="container" style="color: #D0342C;" id="advertencia"><?=htmlspecialchars($mensaje)?></p>
    </form>
    <h1 style="bottom: 10px; right: 20px;position: fixed;">TecnoStock</h1>
    <script src="login.js"></script>
</body>
</html>