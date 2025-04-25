<!DOCTYPE html>
<html>
<head>
    <title>Bienvenido a {{ config('app.name') }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #1d2998; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .footer { margin-top: 20px; padding: 10px; text-align: center; font-size: 12px; color: #777; }
        .button {
            display: inline-block; padding: 10px 20px; background-color: #1d2998;
            color: white; text-decoration: none; border-radius: 5px; margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Bienvenido, {{ $user->user_nombre }}!</h1>
        </div>

        <div class="content">
            <p>Estamos muy contentos de que te hayas unido a {{ config('app.name') }}.</p>

            <p>Con tu cuenta podrás:</p>
            <ul>
                <li>Gestionar tus metas financieras</li>
                <li>Realizar seguimiento de tus ahorros</li>
                <li>Recibir recordatorios personalizados</li>
            </ul>

            <p>¡Comienza ahora mismo a organizar tus finanzas!</p>

            <a href="{{ url('/login') }}" class="button">Ir a mi cuenta</a>

            <p>Si tienes alguna pregunta, no dudes en contactarnos respondiendo a este correo.</p>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
            <p>
                <a href="{{ url('/privacy') }}">Política de Privacidad</a> |
                <a href="{{ url('/terms') }}">Términos de Servicio</a>
            </p>
        </div>
    </div>
</body>
</html>
