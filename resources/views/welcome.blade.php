<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Benvingut - Articles Web</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('CSS/estils.css') }}">
</head>
<body>
    @if(isset($mensajeLogout))
        <div class="alert alert-success">
            {{ $mensajeLogout }}
        </div>
    @endif

    <h2>
        <p class="titol">Selecciona una opció</p><br>
    </h2>
    
    <form method="GET" action="{{ url('/mostrar') }}">
        <button type="submit" class="boto" name="select">Mostrar articles</button>
    </form>

    <a href='{{ url("/login") }}'>
        <button class="login" role="button">Login/Sign up</button>
    </a>
</body>
</html>