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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('CSS/estils.css') }}">
    <title>Modificar Perfil</title>
</head>
<body class="fons_modificar_perfil">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4">
                <div class="text-center mb-4">
                    <img src="{{ $dadesUsuari->imatge ? asset($dadesUsuari->imatge) : asset('Imatges/def_user.jpeg') }}" alt="Avatar" class="rounded-circle" width="150" height="150">
                </div>
                <form action="{{ url('/modificar_perfil') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="nova_imatge" class="form-label">Canviar imatge de perfil</label>
                        <input type="file" name="nova_imatge" id="nova_imatge" class="form-control" accept=".png, .jpg, .jpeg, .webp">
                    </div>
                    <div class="mb-3">
                        <label for="nou_nom" class="form-label">Nom d'usuari</label>
                        <input type="text" name="nou_nom" id="nou_nom" class="form-control" value="{{ $dadesUsuari->usuari }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="correu" class="form-label">Correu electrònic</label>
                        <input type="email" id="correu" class="form-control" value="{{ $dadesUsuari->correo }}" disabled>
                    </div>

                    @if (session('missatge'))
                        <div class='alert alert-danger alert-custom' role='alert'>
                            {!! session('missatge') !!}
                        </div>
                    @endif

                    @if (session('missatge_exit'))
                        <div class='alert alert-success alert-custom' role='alert'>
                            {!! session('missatge_exit') !!}
                        </div>
                    @endif

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">Modificar</button>
                        <a href="{{ url('/index_usuari') }}">
                            <button type="button" class="btn btn-primary btn-lg btn-block" role="button">Anar enrere</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>