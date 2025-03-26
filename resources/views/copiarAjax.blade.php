@include('navbar_view')

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Copiar Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('CSS/estils.css') }}">
    <link rel="stylesheet" href="{{ asset('CSS/estil_formulari.css') }}">
</head>
<body style="background: #6868AC">
    <form method="POST" action="{{ url('/controladorAjax') }}">
        @csrf
        <div class="form">
            <div class="title">Copiar Article</div>
            <div class="subtitle">Modifica el títol i el cos de l'article</div>

            <input type="hidden" name="action" value="copiar_article">
            <input type="hidden" name="article_id" value="{{ $article->id }}">

            <div class="input-container ic2">
                <input name="titol" id="titol" class="input" type="text" placeholder=" " value="{{ $article->titol }}" required />
                <div class="cut"></div>
                <label for="titol" class="placeholder">Títol</label>
            </div>
            <div class="input-container ic2">
                <textarea name="cos" id="cos" class="input textarea" placeholder=" " required>{{ $article->cos }}</textarea>
                <div class="cut cut-short"></div>
                <label for="cos" class="placeholder">Cos</label>
            </div>

            <button type="submit" class="insertar">Copiar</button>
        </div>
    </form>

    <div>
        <a href="{{ url('/index_usuari') }}">
            <button type="button" class="tornar" role="button">Anar enrere</button>
        </a>
    </div>
</body>
</html>