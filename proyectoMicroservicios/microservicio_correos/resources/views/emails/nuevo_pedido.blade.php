<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nuevo Pedido</title>
</head>
<body>
    <h2>¡Gracias por tu pedido, {{ $pedido['cliente'] }}!</h2>

    <p>Has realizado un nuevo pedido con los siguientes detalles:</p>

    <ul>
        <li><strong>Producto:</strong> {{ $pedido['producto'] }}</li>
        <li><strong>Cantidad:</strong> {{ $pedido['cantidad'] }}</li>
        <li><strong>Total:</strong> ${{ $pedido['total'] }}</li>
    </ul>

    <p>Nos pondremos en contacto contigo pronto.</p>
</body>
</html>
