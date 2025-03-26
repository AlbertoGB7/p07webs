@include('navbar_view')

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ asset('CSS/estils.css') }}">
    <title>Modificar article</title>
</head>
<body>
    <p class="titol">Article:</p>
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

    <form method="POST" action="{{ url('/modificar_controlador') }}" class="form-modificar">
        @csrf
        <input type="hidden" name="id" value="{{ $article->ID }}" />
        <input type="hidden" name="field" value="{{ $field }}" />
        
        <label class="titol-chulo" for="new_value">Nou {{ $field === 'titol' ? 'Títol' : 'Cos' }} </label><br>
        <textarea name="new_value" class="textarea"></textarea><br><br>
        <button type="submit" class="boto">Modificar</button>
    </form>

    <a href="{{ url('/index_usuari') }}">
        <button class="tornar" role="button">Anar enrere</button>
    </a>
</body>
</html>