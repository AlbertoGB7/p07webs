@include('navbar_view')

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('CSS/estils.css') }}">
    <title>Gestionar Usuaris</title>
</head>
<body>
<div class="container mt-5">
    <h1 class="titol_elim_usuaris">Gestió d'Usuaris</h1>

    <!-- Mostrar el missatge de èxit si s'ha eliminat un usuari -->
    @if(session('missatge_exit'))
        <div class="alert alert-success" role="alert">
            {{ session('missatge_exit') }}
        </div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nom d'Usuari</th>
                <th>Correu</th>
                <th>Accions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuaris as $usuari)
                <tr>
                    <td>{{ $usuari->usuari }}</td>
                    <td>{{ $usuari->correo }}</td>
                    <td>
                        @if($usuari->rol !== 'admin')
                            <form method="POST" action="{{ route('eliminar_usuari') }}" style="display:inline;">
                                @csrf
                                <input type="hidden" name="id" value="{{ $usuari->id }}">
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        @else
                            <span class="text-muted">No es pot eliminar</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div>
    <a href="{{ url('/index_usuari') }}">
        <button type="button" class="tornar" role="button">Anar enrere</button>
    </a>
</div>

</body>
</html>