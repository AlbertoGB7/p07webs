@include('navbar_view')
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="utf-8" />
    <title>Pràctica 6</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('CSS/estils.css') }}">
</head>

<body>
    <p class="titol">Selecciona una opció</p><br>

    <div class="container mt-4">
        <div class="row justify-content-center">
            {{-- Insertar article (POST) --}}
            <div class="col-auto mb-3 mx-2">
                <form method="POST" action="{{ url('/insertar') }}">
                    @csrf
                    <button type="submit" class="boto" name="insert">Insertar article</button>
                </form>
            </div>

            {{-- Mostrar articles (GET) --}}
            <div class="col-auto mb-3 mx-2">
                <form method="GET" action="{{ url('/mostrar_usuari') }}">
                    <button type="submit" class="boto" name="select">Mostrar articles</button>
                </form>
            </div>

            {{-- Modificar article (GET) --}}
            <div class="col-auto mb-3 mx-2">
                <form method="GET" action="{{ url('/modificar') }}">
                    <button type="submit" class="boto" name="modificar">Modificar article</button>
                </form>
            </div>

            {{-- Eliminar article (POST temporal) --}}
            <div class="col-auto mb-3 mx-2">
                <form method="POST" action="{{ url('/eliminar') }}">
                    @csrf
                    <button type="submit" class="boto" name="eliminar">Eliminar article</button>
                </form>
            </div>

            {{-- Gestionar usuaris (solo admin) --}}
            @if (session('rol') === 'admin')
            <div class="col-auto mb-3 mx-2">
                <a href="{{ url('/gestionar_usuaris') }}" class="btn-link">
                    <button type="button" class="boto">Gestionar Usuaris</button>
                </a>
            </div>
            @endif
        </div>
    </div>

    {{-- Mostrar mensaje de login exitoso (solo primera vez) --}}
    @if (session('login_exitoso'))
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <strong>{{ session('usuari') }}</strong>, t'has loguejat amb èxit
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        {{ session()->forget('login_exitoso') }}
    @endif
</body>
</html>
