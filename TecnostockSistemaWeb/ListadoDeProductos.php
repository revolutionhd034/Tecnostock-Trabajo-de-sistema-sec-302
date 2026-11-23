<?php
require_once"conexion.php";


if(($_GET["limpiar"] ?? "")==="1")
{
    header("Location:ListadoDeProductos.php");
    exit;
}


if($_SERVER["REQUEST_METHOD"]==="POST")
{
    $accion=$_POST["accion"] ?? "";
    $codigo=$_POST["codigo"] ?? "";

    if($accion==="desactivar" && $codigo!=="")
    {
        $consulta=$pdo->prepare("
        UPDATE productos
        SET estado=0
        WHERE codigo=?
        ");

        $consulta->execute([$codigo]);

        header("Location:ListadoDeProductos.php");
        exit;
    }
}


function Listado(PDO $pdo,string $buscar)
{
if($buscar==="")
{
$consulta=$pdo->prepare("
SELECT p.codigo,p.nombre,c.nombre AS categoria,p.precio,
p.stock_actual,p.stock_minimo,p.estado
FROM productos p
INNER JOIN categorias c ON c.id_categoria=p.id_categoria
ORDER BY p.id_producto DESC
");

$consulta->execute();
}
else
{
$consulta=$pdo->prepare("
SELECT p.codigo,p.nombre,c.nombre AS categoria,p.precio,
p.stock_actual,p.stock_minimo,p.estado
FROM productos p
INNER JOIN categorias c ON c.id_categoria=p.id_categoria
WHERE p.nombre LIKE ?
OR p.codigo LIKE ?
OR c.nombre LIKE ?
ORDER BY p.id_producto DESC
");

$consulta->execute([
"%".$buscar."%",
"%".$buscar."%",
"%".$buscar."%"
]);
}

return $consulta->fetchAll();
}


$buscar=trim($_GET["buscar"] ?? "");
$productos=Listado($pdo,$buscar);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Listado De Productos</title>
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
            <a style="color:white;text-decoration:none;" href="Movimientos.php">
                Movimientos
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


    <form method="GET" id="formBuscar" class="container" style="margin-top:30px;">

        <input type="text"
        name="buscar"
        id="buscar"
        class="form-control"
        placeholder="Buscar producto por nombre, código o categoría"
        value="<?=htmlspecialchars($buscar)?>">

        <button type="submit" class="btn btn-primary" style="margin-top:10px;">
            Buscar
        </button>

        <p id="advertencia"></p>

    </form>


    <form method="GET" class="container">

        <button type="submit"
        name="limpiar"
        value="1"
        class="btn btn-secondary">
            Limpiar
        </button>

    </form>


    <table class="container table table-bordered table-striped" style="margin-top:30px;">

        <thead>
        <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Precio</th>
            <th>Stock Actual</th>
            <th>Stock Mínimo</th>
            <th>Stock</th>
            <th>Producto</th>
            <th>Acciones</th>
        </tr>
        </thead>


        <tbody>

        <?php

        if($buscar!=="" && count($productos)==0)
        {
            echo"<tr><td colspan='9'>Producto no encontrado</td></tr>";
        }
        else
        {
        foreach($productos as $producto)
        {
        ?>

        <tr>

            <td><?=htmlspecialchars($producto["codigo"])?></td>

            <td><?=htmlspecialchars($producto["nombre"])?></td>

            <td><?=htmlspecialchars($producto["categoria"])?></td>

            <td>$<?=number_format($producto["precio"],0,",",".")?></td>

            <td><?=$producto["stock_actual"]?></td>

            <td><?=$producto["stock_minimo"]?></td>


            <td>

            <?php

            if($producto["stock_actual"]<$producto["stock_minimo"])
            {
                echo"<span class='text-danger'>Stock bajo</span>";
            }
            else
            {
                echo"<span class='text-success'>Disponible</span>";
            }

            ?>

            </td>


            <td>

            <?php

            if($producto["estado"]==1)
            {
                echo"Activo";
            }
            else
            {
                echo"Inactivo";
            }

            ?>

            </td>


            <td>

                <a class="btn btn-warning"
                href="EditarProducto.php?codigo=<?=urlencode($producto["codigo"])?>">
                    Editar
                </a>


                <?php

                if($producto["estado"]==1)
                {

                ?>

                <form method="POST" style="display:inline;">

                    <input type="hidden"
                    name="codigo"
                    value="<?=htmlspecialchars($producto["codigo"])?>">


                    <button type="submit"
                    name="accion"
                    value="desactivar"
                    class="btn btn-danger">
                        Desactivar
                    </button>

                </form>

                <?php

                }
                else
                {
                    echo" Desactivado";
                }

                ?>

            </td>

        </tr>

        <?php

        }
        }

        ?>

        </tbody>

    </table>


    <h1 style="bottom:10px;right:20px;position:fixed;">
        TecnoStock
    </h1>


    <script src="Listado.js"></script>

</body>
</html>