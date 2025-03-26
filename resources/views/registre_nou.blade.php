<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ asset('CSS/estils.css') }}">
</head>
<body class="fons_registre">
<section>
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card card-blur shadow-2-strong text-white">
          <div class="card-body p-5 text-center">
              <h3 class="mb-5">Registre</h3>

              {{-- Missatges d'error --}}
              @if (session('missatge'))
                  <div class='alert alert-danger alert-custom' role='alert'>
                      {!! session('missatge') !!}
                  </div>
              @endif

              {{-- Missatge d'èxit --}}
              @if (session('missatge_exit'))
                  <div class='alert alert-success alert-custom' role='alert'>
                      {!! session('missatge_exit') !!}
                  </div>
              @endif

              <form method="POST" action="{{ route('registre') }}">
                  @csrf
                  <input type="hidden" name="accion" value="registro">

                  <div class="form-outline mb-4">
                      <label class="form-label" for="typeUsuari">Usuari</label>
                      <input type="text" id="typeUsuari" name="usuari_reg" class="form-control form-control-lg bg-dark text-white" value="{{ old('usuari_reg', session('usuari_reg')) }}" />
                  </div>

                  <div class="form-outline mb-4">
                      <label class="form-label" for="typeEmail">Correu</label>
                      <input type="email" id="typeEmail" name="email_reg" class="form-control form-control-lg bg-dark text-white" value="{{ old('email_reg', session('email_reg')) }}" />
                  </div>

                  <div class="form-outline mb-4">
                      <label class="form-label" for="typePassword">Password</label>
                      <input type="password" id="typePassword" name="pass" class="form-control form-control-lg bg-dark text-white" />
                  </div>

                  <div class="form-outline mb-4">
                      <label class="form-label" for="typeConfirmPassword">Confirmar Password</label>
                      <input type="password" id="typeConfirmPassword" name="confirm_pass" class="form-control form-control-lg bg-dark text-white" />
                  </div>

                  <button class="btn btn-primary mt-3 btn-lg btn-block" type="submit">Registrarse</button>

                  <p class="mt-3">Ja tens compte?
                    <a href="{{ url('/login') }}" class="text-decoration-none text-primary">Inicia sessió</a>
                  </p>
              </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>
