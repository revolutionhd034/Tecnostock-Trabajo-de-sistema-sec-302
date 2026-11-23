<?php
require_once"conexion.php";

$mensaje="";
$correcto="";

$consultaProductos=$pdo->prepare("
SELECT id_producto,codigo,nombre,stock_actual
FROM productos
WHERE estado=1
ORDER BY nombre
");

$consultaProductos->execute();
$productos=$consultaProductos->fetchAll();


if(($_GET["registrado"] ?? "")==="1")
{
    $correcto="Movimiento registrado correctamente";
}


if($_SERVER["REQUEST_METHOD"]==="POST")
{
$idProducto=$_POST["id_producto"] ?? "";
$tipo=$_POST["tipo"] ?? "";
$cantidad=$_POST["cantidad"] ?? "";
$observacion=trim($_POST["observacion"] ?? "");


if($idProducto==="" || $tipo==="" || $cantidad==="")
{
    $mensaje="Complete todos los campos obligatorios";
}
else if($cantidad<=0)
{
    $mensaje="La cantidad debe ser mayor a 0";
}
else if($tipo!=="ENTRADA" && $tipo!=="SALIDA")
{
    $mensaje="Tipo de movimiento inválido";
}
else
{
    $consulta=$pdo->prepare("
    SELECT id_producto,stock_actual
    FROM productos
    WHERE id_producto=? AND estado=1
    ");

    $consulta->execute([$idProducto]);
    $producto=$consulta->fetch();


    if(!$producto)
    {
        $mensaje="Producto no encontrado";
    }
    else if($tipo==="SALIDA" && $cantidad>$producto["stock_actual"])
    {
        $mensaje="No existe stock suficiente para realizar la salida";
    }
    else
    {
        $consultaUsuario=$pdo->prepare("
        SELECT id_usuario
        FROM usuarios
        WHERE correo=?
        ");

        $consultaUsuario->execute([
            "encargado@tecnostock.cl"
        ]);

        $usuario=$consultaUsuario->fetch();


        if(!$usuario)
        {
            $mensaje="Usuario no encontrado";
        }
        else
        {
            try
            {
                $pdo->beginTransaction();


                $consulta=$pdo->prepare("
                INSERT INTO movimientos
                (tipo,cantidad,observacion,id_producto,id_usuario)
                VALUES(?,?,?,?,?)
                ");

                $consulta->execute([
                    $tipo,
                    $cantidad,
                    $observacion,
                    $idProducto,
                    $usuario["id_usuario"]
                ]);


                if($tipo==="ENTRADA")
                {
                    $consulta=$pdo->prepare("
                    UPDATE productos
                    SET stock_actual=stock_actual+?
                    WHERE id_producto=?
                    ");
                }
                else
                {
                    $consulta=$pdo->prepare("
                    UPDATE productos
                    SET stock_actual=stock_actual-?
                    WHERE id_producto=?
                    ");
                }


                $consulta->execute([
                    $cantidad,
                    $idProducto
                ]);


                $pdo->commit();


                header("Location:Movimientos.php?registrado=1");
                exit;
            }
            catch(PDOException $error)
            {
                $pdo->rollBack();

                $mensaje="No fue posible registrar el movimiento";
            }
        }
    }
}
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Movimientos</title>
</head>

<body style="margin:auto;justify-content:center;display:flex;flex-direction:column;">

    <div style="background:#005582;width:100%;">
         <ul>
        <nav style="display:flex;list-style:none;margin-top:1%;">

         <li class="container" style="margin-right:20px;white-space:nowrap;background:#005582;">
            <a style="color:white;text-decoration:none;" href="RegistrarProductos.php">
                Registro De Productos
            </a>
         </li>

         <li class="container" style="margin-left:20px;white-space:nowrap;background:#005582;">
            <a style="color:white;text-decoration:none;" href="ListadoDeProductos.php">
                Listado De Productos
            </a>
         </li>

         <li class="container" style="margin-left:20px;background:#005582;">
            <a style="color:white;text-decoration:none;" href="index.html">
                Pagina Principal
            </a>
         </li>

        </nav>
         </ul>
    </div>


    <form method="POST" id="formMovimiento" class="container" style="margin-top:40px;width:50%;">

        <h2 style="text-align:center;">Movimiento De Existencias</h2>


        <label class="form-label">Producto</label>

        <select name="id_producto" id="id_producto" class="form-select">

            <option value="">Seleccione un producto</option>

            <?php
            foreach($productos as $producto)
            {
            ?>

            <option value="<?=$producto["id_producto"]?>">
                <?=htmlspecialchars($producto["codigo"])?>
                -
                <?=htmlspecialchars($producto["nombre"])?>
                -
                Stock: <?=$producto["stock_actual"]?>
            </option>

            <?php
            }
            ?>

        </select>


        <label class="form-label" style="margin-top:10px;">Tipo De Movimiento</label>

        <select name="tipo" id="tipo" class="form-select">

            <option value="">Seleccione el movimiento</option>
            <option value="ENTRADA">Entrada</option>
            <option value="SALIDA">Salida</option>

        </select>


        <label class="form-label" style="margin-top:10px;">Cantidad</label>

        <input type="number"
        name="cantidad"
        id="cantidad"
        class="form-control"
        placeholder="Ingrese la cantidad">


        <label class="form-label" style="margin-top:10px;">Observación</label>

        <input type="text"
        name="observacion"
        id="observacion"
        class="form-control"
        placeholder="Ingrese una observación">


        <button type="submit" class="btn btn-primary" style="margin-top:15px;">
            Registrar Movimiento
        </button>


        <p id="advertencia" style="color:#D0342C;margin-top:10px;">
            <?=htmlspecialchars($mensaje)?>
        </p>


        <p style="color:green;">
            <?=htmlspecialchars($correcto)?>
        </p>

    </form>


    <h1 style="bottom:10px;right:20px;position:fixed;">
        TecnoStock
    </h1>


    <script src="Movimientos.js"></script>

</body>
</html>