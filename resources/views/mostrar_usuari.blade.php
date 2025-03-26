@include('navbar_view')

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
    <p class="titol">Taula d'articles</p><br><br>

    @if (session('missatge_exit'))
        <p style="color: green; font-family: 'Calligraffitti', cursive;">{{ session('missatge_exit') }}</p>
    @elseif (session('missatge'))
        <p style="color: red; font-family: 'Calligraffitti', cursive;">{{ session('missatge') }}</p>
    @endif

    <div class="search-container">
        <form method="GET" action="{{ route('mostrar_usuari') }}" class="search-box">
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
                    <tr>
                        <th>ID</th>
                        <th>Títol</th>
                        <th>Cos</th>
                        <th>Accions</th>
                    </tr>
                    @foreach($resultats as $res)
                        <tr>
                            <td>{{ $res->ID }}</td>
                            <td>{{ $res->titol }}</td>
                            <td>{{ $res->cos }}</td>
                            <td>
                                <button class='btn btn-secondary btn-lg dropdown-toggle mx-5 dropup' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                    <img src='{{ asset('Imatges/qr_icon.png') }}' alt='QR' style='width:24px; height:24px;'>
                                </button>
                                <ul class='dropdown-menu'>
                                    <li><a class='dropdown-item' href='{{ url("/controladorAjax") }}?action=compartir_article&article_id={{ $res->ID }}'>Compartir article</a></li>
                                    <li><a class='dropdown-item' href='{{ route("generar_qr", ["id" => $res->ID]) }}'>Descarregar QR</a></li>
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </h2>
    </div>

    <div class="pagination">
        @if ($pagina_actual > 1)
            <a href="?pagina=1&articles_per_pagina={{ $articles_per_pagina }}&orden={{ $orden }}&terme={{ urlencode($terme_cerca) }}">&laquo;</a>
            <a href="?pagina={{ $pagina_actual - 1 }}&articles_per_pagina={{ $articles_per_pagina }}&orden={{ $orden }}&terme={{ urlencode($terme_cerca) }}">&lsaquo;</a>
        @endif

        @for ($i = 1; $i <= $total_pagines; $i++)
            <a @if($i == $pagina_actual) class='active' @endif href="?pagina={{ $i }}&articles_per_pagina={{ $articles_per_pagina }}&orden={{ $orden }}&terme={{ urlencode($terme_cerca) }}">{{ $i }}</a>
        @endfor

        @if ($pagina_actual < $total_pagines)
            <a href="?pagina={{ $pagina_actual + 1 }}&articles_per_pagina={{ $articles_per_pagina }}&orden={{ $orden }}&terme={{ urlencode($terme_cerca) }}">&rsaquo;</a>
            <a href="?pagina={{ $total_pagines }}&articles_per_pagina={{ $articles_per_pagina }}&orden={{ $orden }}&terme={{ urlencode($terme_cerca) }}">&raquo;</a>
        @endif
    </div>

    <div class="box">
        <select id="articles" onchange="location = this.value;">
            <option value="?pagina=1&articles_per_pagina=5&orden={{ $orden }}&terme={{ urlencode($terme_cerca) }}" {{ $articles_per_pagina == 5 ? 'selected' : '' }}>5 articles</option>
            <option value="?pagina=1&articles_per_pagina=10&orden={{ $orden }}&terme={{ urlencode($terme_cerca) }}" {{ $articles_per_pagina == 10 ? 'selected' : '' }}>10 articles</option>
            <option value="?pagina=1&articles_per_pagina=15&orden={{ $orden }}&terme={{ urlencode($terme_cerca) }}" {{ $articles_per_pagina == 15 ? 'selected' : '' }}>15 articles</option>
        </select>
    </div>

    <div class="box_ord">
        <select id="articles" onchange="location = this.value;">
            <option value="?pagina=1&articles_per_pagina={{ $articles_per_pagina }}&orden=data_desc&terme={{ urlencode($terme_cerca) }}" {{ $orden === 'data_desc' ? 'selected' : '' }}>Data (DESC)</option>
            <option value="?pagina=1&articles_per_pagina={{ $articles_per_pagina }}&orden=data_asc&terme={{ urlencode($terme_cerca) }}" {{ $orden === 'data_asc' ? 'selected' : '' }}>Data (ASC)</option>
            <option value="?pagina=1&articles_per_pagina={{ $articles_per_pagina }}&orden=titol_desc&terme={{ urlencode($terme_cerca) }}" {{ $orden === 'titol_desc' ? 'selected' : '' }}>Títol (DESC)</option>
            <option value="?pagina=1&articles_per_pagina={{ $articles_per_pagina }}&orden=titol_asc&terme={{ urlencode($terme_cerca) }}" {{ $orden === 'titol_asc' ? 'selected' : '' }}>Títol (ASC)</option>
        </select>
    </div>

    <div>
        <a href="{{ url('/index_usuari') }}">
            <button type="button" class="tornar" role="button">Anar enrere</button>
        </a>
    </div>
</body>
</html>
