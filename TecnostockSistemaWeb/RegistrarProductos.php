<?php
require_once"conexion.php";

$mensaje="";

$consultaCategorias=$pdo->prepare("
SELECT id_categoria,nombre
FROM categorias
WHERE estado=1
");

$consultaCategorias->execute();
$categorias=$consultaCategorias->fetchAll();


if($_SERVER["REQUEST_METHOD"]==="POST")
{
$codigo=strtoupper(trim($_POST["codigo"] ?? ""));
$nombre=trim($_POST["nombre"] ?? "");
$descripcion=trim($_POST["descripcion"] ?? "");
$precio=$_POST["precio"] ?? "";
$stockActual=$_POST["stock_actual"] ?? "";
$stockMinimo=$_POST["stock_minimo"] ?? "";
$categoria=$_POST["id_categoria"] ?? "";


if($codigo==="" || $nombre==="" || $precio==="" || $stockActual==="" || $stockMinimo==="" || $categoria==="")
{
    $mensaje="Complete todos los campos obligatorios";
}
else if($precio<0 || $stockActual<0 || $stockMinimo<0)
{
    $mensaje="El precio y los stock no pueden ser negativos";
}
else
{
    $consulta=$pdo->prepare("
    SELECT * FROM productos
    WHERE codigo=?
    ");

    $consulta->execute([$codigo]);

    $producto=$consulta->fetch();

    if($producto)
    {
        $mensaje="El código del producto ya existe";
    }
    else
    {
        $consulta=$pdo->prepare("
        INSERT INTO productos
        (codigo,nombre,descripcion,precio,stock_actual,stock_minimo,estado,id_categoria)
        VALUES(?,?,?,?,?,?,?,?)
        ");

        $consulta->execute([
            $codigo,
            $nombre,
            $descripcion,
            $precio,
            $stockActual,
            $stockMinimo,
            1,
            $categoria
        ]);

        header("Location:ListadoDeProductos.php");
        exit;
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
    <title>Registro De Productos</title>
</head>

<body style="margin:auto;justify-content:center;display:flex;flex-direction:column;">

    <div style="background:#005582;width:100%;">
         <ul>
        <nav style="display:flex;list-style:none;margin-top:1%;">

         <li class="container" style="margin-right:20px;white-space:nowrap;background:#005582;">
            <a style="color:white;text-decoration:none;" href="ListadoDeProductos.php">
                Listado De Productos
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


    <form method="POST" id="formRegistro" class="container" style="margin-top:40px;width:50%;">

        <h2 style="text-align:center;">Registro De Productos</h2>


        <label class="form-label">Código</label>
        <input type="text"
        name="codigo"
        id="codigo"
        class="form-control"
        placeholder="Ingrese el código">


        <label class="form-label" style="margin-top:10px;">Nombre</label>
        <input type="text"
        name="nombre"
        id="nombre"
        class="form-control"
        placeholder="Ingrese el nombre">


        <label class="form-label" style="margin-top:10px;">Descripción</label>
        <input type="text"
        name="descripcion"
        id="descripcion"
        class="form-control"
        placeholder="Ingrese una descripción">


        <label class="form-label" style="margin-top:10px;">Precio</label>
        <input type="number"
        name="precio"
        id="precio"
        class="form-control"
        placeholder="Ingrese el precio">


        <label class="form-label" style="margin-top:10px;">Stock Actual</label>
        <input type="number"
        name="stock_actual"
        id="stock_actual"
        class="form-control"
        placeholder="Ingrese el stock actual">


        <label class="form-label" style="margin-top:10px;">Stock Mínimo</label>
        <input type="number"
        name="stock_minimo"
        id="stock_minimo"
        class="form-control"
        placeholder="Ingrese el stock mínimo">


        <label class="form-label" style="margin-top:10px;">Categoría</label>

        <select name="id_categoria" id="id_categoria" class="form-select">

            <option value="">Seleccione una categoría</option>

            <?php
            foreach($categorias as $categoria)
            {
            ?>

            <option value="<?=$categoria["id_categoria"]?>">
                <?=htmlspecialchars($categoria["nombre"])?>
            </option>

            <?php
            }
            ?>

        </select>


        <button type="submit" class="btn btn-primary" style="margin-top:15px;">
            Registrar Producto
        </button>


        <p id="advertencia" style="color:#D0342C;margin-top:10px;">
            <?=htmlspecialchars($mensaje)?>
        </p>

    </form>


    <h1 style="bottom:10px;right:20px;position:fixed;">
        TecnoStock
    </h1>


    <script src="RegistrarProducto.js"></script>

</body>
</html>