<?php
require_once"conexion.php";

$mensaje="";
$codigo=$_GET["codigo"] ?? $_POST["codigo"] ?? "";

$consulta=$pdo->prepare("
SELECT * FROM productos
WHERE codigo=?
");

$consulta->execute([$codigo]);
$producto=$consulta->fetch();

if(!$producto)
{
    exit("Producto no encontrado");
}

$categorias=$pdo->prepare("
SELECT id_categoria,nombre
FROM categorias
WHERE estado=1
");

$categorias->execute();
$listaCategorias=$categorias->fetchAll();

if($_SERVER["REQUEST_METHOD"]==="POST")
{
    $precio=$_POST["precio"] ?? "";
    $descripcion=trim($_POST["descripcion"] ?? "");
    $stockActual=$_POST["stock_actual"] ?? "";
    $stockMinimo=$_POST["stock_minimo"] ?? "";
    $categoria=$_POST["id_categoria"] ?? "";

    if($precio==="" || $stockActual==="" || $stockMinimo==="" || $categoria==="")
    {
        $mensaje="Complete todos los campos obligatorios";
    }
    else if($precio<0 || $stockActual<0 || $stockMinimo<0)
    {
        $mensaje="Los valores no pueden ser negativos";
    }
    else
    {
        $actualizar=$pdo->prepare("
        UPDATE productos
        SET precio=?,
        descripcion=?,
        stock_actual=?,
        stock_minimo=?,
        id_categoria=?
        WHERE codigo=?
        ");

        $actualizar->execute([
            $precio,
            $descripcion,
            $stockActual,
            $stockMinimo,
            $categoria,
            $codigo
        ]);

        header("Location:ListadoDeProductos.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Editar Producto</title>
</head>

<body>

<form method="POST" class="container" style="margin-top:30px;">

    <h2>Editar Producto</h2>

    <input type="hidden" name="codigo"
    value="<?=htmlspecialchars($producto["codigo"])?>">

    <label class="form-label">Código</label>
    <input type="text" class="form-control"
    value="<?=htmlspecialchars($producto["codigo"])?>" disabled>

    <label class="form-label">Nombre</label>
    <input type="text" class="form-control"
    value="<?=htmlspecialchars($producto["nombre"])?>" disabled>

    <label class="form-label">Descripción</label>
    <input type="text" name="descripcion" class="form-control"
    value="<?=htmlspecialchars($producto["descripcion"] ?? "")?>">

    <label class="form-label">Precio</label>
    <input type="number" name="precio" class="form-control"
    value="<?=$producto["precio"]?>">

    <label class="form-label">Categoría</label>
    <select name="id_categoria" class="form-select">

        <?php
        foreach($listaCategorias as $categoria)
        {
        ?>

        <option value="<?=$categoria["id_categoria"]?>"
        <?php
        if($categoria["id_categoria"]==$producto["id_categoria"])
        {
            echo"selected";
        }
        ?>>
        <?=htmlspecialchars($categoria["nombre"])?>
        </option>

        <?php
        }
        ?>

    </select>

    <label class="form-label">Stock Actual</label>
    <input type="number" name="stock_actual" class="form-control"
    value="<?=$producto["stock_actual"]?>">

    <label class="form-label">Stock Mínimo</label>
    <input type="number" name="stock_minimo" class="form-control"
    value="<?=$producto["stock_minimo"]?>">

    <p style="color:red;"><?=$mensaje?></p>

    <button type="submit" class="btn btn-primary">
        Guardar Cambios
    </button>

    <a href="ListadoDeProductos.php" class="btn btn-secondary">
        Volver
    </a>

</form>

<script src="EditarProducto.js"></script>

</body>
</html>