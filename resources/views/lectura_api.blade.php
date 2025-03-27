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
    <title>Clash of Clans API</title>
    <link rel="stylesheet" href="{{ asset('CSS/estils.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body><br><br><br>
    <h1>Perfil Clash of Clans</h1>
    
    <div class="estil_lectura_api">
        <div class="estil_lectura_api_clan">
            <h2>Clan</h2>
            @if ($clanInfo)
                <img src="{{ $clanInfo['badgeUrls']['large'] }}" alt="Clan Badge">
                <p><strong>Nom:</strong> {{ $clanInfo['name'] }}</p>
                <p><strong>Nivell:</strong> {{ $clanInfo['clanLevel'] }}</p>
                <p><strong>Membres:</strong> {{ $clanInfo['members'] }}</p>
            @else
                <p class="error">No s'ha pogut obtenir la informació.</p>
            @endif
        </div>

        <div class="estil_lectura_api_jugador">
            <h2>Jugador</h2>
            @if ($playerInfo)
                <img src="https://api-assets.clashofclans.com/leagues/72/{{ $playerInfo['league']['id'] }}.png" alt="Player League">
                <p><strong>Nom:</strong> {{ $playerInfo['name'] }}</p>
                <p><strong>Nivell:</strong> {{ $playerInfo['expLevel'] }}</p>
                <p><strong>Copes:</strong> {{ $playerInfo['trophies'] }}</p>
            @else
                <p class="error">No s'ha pogut obtenir la informació.</p>
            @endif
        </div>
    </div>

    <div>
        <a href="{{ url('/index_usuari') }}">
            <button type="button" class="tornar" role="button">Anar enrere</button>
        </a>
    </div>
</body>
</html>