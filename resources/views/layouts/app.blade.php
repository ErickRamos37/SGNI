<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Proyecto Laravel</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])

</head>
<body>

    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Mi Web en HostGator</a>
        </div>
    </nav>

    <main class="mt-4">
        @yield('contenido')
    </main>

</body>
</html>