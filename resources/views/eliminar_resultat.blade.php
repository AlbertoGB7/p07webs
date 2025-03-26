@include('navbar_view')

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultat eliminació</title>
    <link rel="stylesheet" href="{{ asset('CSS/estils.css') }}">
</head>
<body>

    <p class="titol">{{ $titol }}</p>

    @if (isset($article))
        <br><br><br><br>
        <div class="table-wrapper">
            <table class="fl-table">
                <tr><th>ID</th><th>Títol</th><th>Cos</th></tr>
                <tr>
                    <td>{{ $article->ID }}</td>
                    <td>{{ $article->titol }}</td>
                    <td>{{ $article->cos }}</td>
                </tr>
            </table>
        </div>

        <form method="POST" action="{{ url('/eliminar') }}">
            @csrf
            <input type="hidden" name="id" value="{{ $article->ID }}">
            <input type="submit" value="Eliminar" class="boto" name="eliminar"><br><br>
        </form>

        <a href="{{ url('/eliminar') }}">
            <button class="tornar" role="button">Anar enrere</button>
        </a>

    @else
        <a href="{{ url('/index_usuari') }}">
            <button class="tornar" role="button">Anar enrere</button>
        </a>
    @endif

</body>
</html>
