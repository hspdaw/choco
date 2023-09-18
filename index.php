<!DOCTYPE html>
<html>

<head>
    <title>Resultado de los Pedidos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="public/css/bootstrap5.css" rel="stylesheet">
    <script src="public/js/bootstrap5.js"></script>
</head>

<body>
    <?php
    include 'app/controllers/pedidos.php';
    $controlador = new PedidoController();
    $controlador->gestionarPedidos();
    ?>
</body>

</html>