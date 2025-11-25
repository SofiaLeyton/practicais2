<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Recuperación de contraseña</title>
</head>
<body>
    <h2>Solicitud de restablecimiento de contraseña</h2>
    <p>Has solicitado restablecer tu contraseña. Usa este código:</p>

    <h3 style="color: #2d3748;"><?php echo e($token); ?></h3>

    <p>Si no solicitaste este cambio, puedes ignorar este correo.</p>
</body>
</html>
<?php /**PATH C:\laragon\www\microservicio_seguridad\resources\views/emails/reset_password.blade.php ENDPATH**/ ?>