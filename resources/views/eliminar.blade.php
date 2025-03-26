<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */
?>
@include('navbar_view')

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ asset('CSS/estil_formulari.css') }}">
    <title>Eliminar article</title>
</head>
<body style="background: #6868AC">
    <h2>
        <form method="POST" action="{{ url('/eliminar') }}">
            @csrf
            <div class="form">
                <div class="title">Eliminar article</div>
                <div class="subtitle">Elimina tot l'article amb el seu ID</div>
                <div class="input-container ic1">
                    <input name="id" class="input" type="text" placeholder=" " value="{{ session('id', '') }}" />
                    <div class="cut"></div>
                    <label for="id" class="placeholder"></label>
                </div>
                <br>
                <input type="submit" value="Buscar" class="insertar" name="buscar">
                <br><br>
                @if (session('missatge_exit'))
                    <p style="color: green; font-family: 'Calligraffitti', cursive;">{{ session('missatge_exit') }}</p>
                @elseif (session('missatge'))
                    <p style="color: red; font-family: 'Calligraffitti', cursive;">{{ session('missatge') }}</p>
                @endif
            </div>
        </form>
    </h2>

    <div>
        <a href="{{ url('/index_usuari') }}">
            <button type="button" class="tornar" role="button">Anar enrere</button>
        </a>
    </div>
</body>
</html>