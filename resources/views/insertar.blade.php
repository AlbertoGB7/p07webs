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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <link rel="stylesheet" type="text/css" href="{{ asset('CSS/estil_formulari.css') }}">
</head>
<body style="background: #6868AC">   

    <form method="POST" action="{{ url('/insertar_controlador') }}">
        @csrf
        <div class="form">
            <div class="title">Insertar article</div>
            <div class="subtitle">Afegeix el teu article! Títol i Cos</div>

            <div class="input-container ic2">
                <input name="titol" class="input" type="text" placeholder=" " value="{{ session('titol') ?? '' }}" />
                <div class="cut"></div>
                <label for="titol" class="placeholder"></label>
            </div>
            <div class="input-container ic2">
                <input name="cos" class="input" type="text" placeholder=" " value="{{ session('cos') ?? '' }}" />
                <div class="cut cut-short"></div>
                <label for="cos" class="placeholder"></label>
            </div>
            <br>
            <input type="submit" value="Insertar" class="insertar" name="insert">
            <br><br>

            {{-- Missatge d'èxit --}}
            @if(session('missatge_exit'))
                <p style="color: green; font-family: 'Calligraffitti', cursive;">{{ session('missatge_exit') }}</p>
            @elseif(session('missatge'))
                <p style="color: red; font-family: 'Calligraffitti', cursive;">{{ session('missatge') }}</p>
            @endif

        </div>
    </form>

    <div>
        <a href="{{ url('/index_usuari') }}">
            <button type="button" class="tornar" role="button">Anar enrere</button>
        </a>
    </div>

</body>
</html>
