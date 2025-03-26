<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('CSS/estils.css') }}">
    <title>Taula d'articles</title>
</head>
<body>
    <p class="titol">Taula d'articles</p>
    
    <a href='{{ url("/login") }}'>
        <button class="login" role="button">Login/Sign up</button>
    </a>

    <a href='{{ url("/") }}'>
        <button class="tornar_mostrar" role="button">Anar enrere</button>
    </a>

    <!-- Barra de cerca -->
    <div class="search-container">
        <form method="GET" action="{{ route('mostrar') }}" class="search-box">
            <input type="hidden" name="pagina" value="1">
            <input type="hidden" name="articles_per_pagina" value="{{ $articles_per_pagina }}">
            <input type="text" class="boton-search" name="terme" placeholder="Titol..." value="{{ $terme_cerca }}">
            <button type="submit" class="boton-lupa"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <br><br>
    <div class="articulos">
        <h2>
            <div class='table-wrapper'>
                <table class='fl-table'>
                    <thead>
                        <tr>
                            <th>Títol</th>
                            <th>Cos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resultats as $res)
                            <tr>
                                <td>{{ $res->titol }}</td>
                                <td>{{ $res->cos }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </h2>
    </div>

    <!-- Paginació -->
    @if($total_articles > 0)
        <div class='pagination'>
            @if($pagina_actual > 1)
                <a href="{{ route('mostrar', ['pagina' => 1, 'articles_per_pagina' => $articles_per_pagina, 'orden' => $orden, 'terme' => $terme_cerca]) }}">&laquo;</a>
                <a href="{{ route('mostrar', ['pagina' => $pagina_actual - 1, 'articles_per_pagina' => $articles_per_pagina, 'orden' => $orden, 'terme' => $terme_cerca]) }}">&lsaquo;</a>
            @endif

            @for($i = 1; $i <= $total_pagines; $i++)
                @if($i == $pagina_actual)
                    <a class='active' href="{{ route('mostrar', ['pagina' => $i, 'articles_per_pagina' => $articles_per_pagina, 'orden' => $orden, 'terme' => $terme_cerca]) }}">{{ $i }}</a>
                @else
                    <a href="{{ route('mostrar', ['pagina' => $i, 'articles_per_pagina' => $articles_per_pagina, 'orden' => $orden, 'terme' => $terme_cerca]) }}">{{ $i }}</a>
                @endif
            @endfor

            @if($pagina_actual < $total_pagines)
                <a href="{{ route('mostrar', ['pagina' => $pagina_actual + 1, 'articles_per_pagina' => $articles_per_pagina, 'orden' => $orden, 'terme' => $terme_cerca]) }}">&rsaquo;</a>
                <a href="{{ route('mostrar', ['pagina' => $total_pagines, 'articles_per_pagina' => $articles_per_pagina, 'orden' => $orden, 'terme' => $terme_cerca]) }}">&raquo;</a>
            @endif
        </div>
    @endif

    <div class="box">
        <select id="articles" onchange="location = this.value;">
            <option value="{{ route('mostrar', ['pagina' => $pagina_actual, 'articles_per_pagina' => 5, 'orden' => $orden, 'terme' => $terme_cerca]) }}" {{ $articles_per_pagina == 5 ? 'selected' : '' }}>5 articles</option>
            <option value="{{ route('mostrar', ['pagina' => $pagina_actual, 'articles_per_pagina' => 10, 'orden' => $orden, 'terme' => $terme_cerca]) }}" {{ $articles_per_pagina == 10 ? 'selected' : '' }}>10 articles</option>
            <option value="{{ route('mostrar', ['pagina' => $pagina_actual, 'articles_per_pagina' => 15, 'orden' => $orden, 'terme' => $terme_cerca]) }}" {{ $articles_per_pagina == 15 ? 'selected' : '' }}>15 articles</option>
        </select>
    </div>

    <div class="box_ord">
        <select id="articles" onchange="location = this.value;">
            <option value="{{ route('mostrar', ['pagina' => 1, 'articles_per_pagina' => $articles_per_pagina, 'orden' => 'data_desc', 'terme' => $terme_cerca]) }}" {{ $orden === 'data_desc' ? 'selected' : '' }}>Data (DESC)</option>
            <option value="{{ route('mostrar', ['pagina' => 1, 'articles_per_pagina' => $articles_per_pagina, 'orden' => 'data_asc', 'terme' => $terme_cerca]) }}" {{ $orden === 'data_asc' ? 'selected' : '' }}>Data (ASC)</option>
            <option value="{{ route('mostrar', ['pagina' => 1, 'articles_per_pagina' => $articles_per_pagina, 'orden' => 'titol_desc', 'terme' => $terme_cerca]) }}" {{ $orden === 'titol_desc' ? 'selected' : '' }}>Alfabèticament (DESC)</option>
            <option value="{{ route('mostrar', ['pagina' => 1, 'articles_per_pagina' => $articles_per_pagina, 'orden' => 'titol_asc', 'terme' => $terme_cerca]) }}" {{ $orden === 'titol_asc' ? 'selected' : '' }}>Alfabèticament (ASC)</option>
        </select>
    </div>
</body>
</html>