<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('CSS/estils.css') }}">
    <title>Restablir Contrasenya</title>
</head>
<body class="fons_canvi_pass">
<section>
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card card-blur shadow-2-strong text-white">
          <div class="card-body p-5 text-center">
              <h3 class="titol_canvi_pass">Restablir Contrasenya</h3>

              @if(session('missatge'))
                  <div class='alert alert-danger alert-custom' role='alert'>
                      {!! session('missatge') !!}
                  </div>
              @endif

              @if(session('missatge_exit'))
                  <div class='alert alert-success alert-custom' role='alert'>
                      {!! session('missatge_exit') !!}
                  </div>
              @endif

              <form method="POST" action="{{ url('/enviar_correu') }}">
                  @csrf
                  <div class="form-outline mb-4">
                      <label for="correu" class="form-label text-dark">Correu electrònic</label>
                      <input type="email" id="correu" name="correu" class="form-control bg-dark text-white">
                  </div>

                  <button class="btn btn-primary btn-lg btn-block" type="submit">Enviar</button>
                  
                  <div class="mt-3">
                      <a href="{{ url('/login') }}">
                          <button type="button" class="btn btn-secondary" role="button">Tornar al login</button>
                      </a>
                  </div>
              </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>