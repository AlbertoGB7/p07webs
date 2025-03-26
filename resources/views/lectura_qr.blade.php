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
    <title>Pujar QR</title>
    <link rel="stylesheet" href="{{ asset('CSS/estils.css') }}">
</head>
<body>
    <div class="container">
        <br><br><br><h3 class="titol_canvi_pass">Pujar QR</h3>

        @if(session('missatge_exit'))
            <div class="alert alert-success" role="alert">
                {{ session('missatge_exit') }}
            </div>
        @endif

        @if(session('missatge'))
            <div class="alert alert-danger" role="alert">
                {{ session('missatge') }}
            </div>
        @endif

        <form action="{{ route('processar_qr') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <input type="file" name="qr_file" id="qr_file" class="form-control" accept=".png, .jpg, .jpeg">
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Leer QR</button>
            </div>
        </form>

        <a href="{{ url('/vistaAjax') }}">
            <button type="button" class="tornar">Ver artículos compartidos</button>
        </a>
    </div>
</body>
</html>