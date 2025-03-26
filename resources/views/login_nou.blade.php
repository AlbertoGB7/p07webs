<!DOCTYPE html>
<html lang="ca">
<head>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ asset('CSS/estils.css') }}">
</head>
<body class="fons_login">
<section>
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card card-blur shadow-2-strong text-white">
          <div class="card-body p-5 text-center">
              <h3 class="mb-5">Login</h3>

              {{-- Missatges d'error o èxit --}}
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

              <form method="POST" action="{{ route('login') }}">
                  @csrf
                  <input type="hidden" name="accion" value="login">
                  
                  <div class="form-outline mb-4">
                      <label class="form-label" for="usuari">Usuari</label>
                      <input type="text" id="usuari" name="usuari" class="form-control form-control-lg bg-dark text-white" value="{{ old('usuari', session('usuari')) }}" />
                  </div>

                  <div class="form-outline mb-4">
                      <label class="form-label" for="email">Correu</label>
                      <input type="email" id="email" name="email" class="form-control form-control-lg bg-dark text-white" value="{{ old('email', session('email')) }}" />
                  </div>

                  <div class="form-outline mb-4">
                      <label class="form-label" for="pass">Password</label>
                      <input type="password" id="pass" name="pass" class="form-control form-control-lg bg-dark text-white" />
                  </div>

                  <label>
                      <input type="checkbox" name="recordar"> Recordar-me
                  </label>

                  <p class="mt-3">Et vols registrar? 
                    <a href="{{ url('/registre') }}" class="text-decoration-none text-primary">Registrarse</a>
                  </p>

                  <p class="mt-3">Has oblidat la contrasenya?
                    <a href="{{ url('/enviar_correu') }}" class="text-decoration-none text-primary">Restablir contrasenya</a>
                  </p>

                  @if (session('intentos_fallidos') && session('intentos_fallidos') >= 3)
                      <div class="recaptcha-container">
                          <div class="g-recaptcha" data-sitekey="6LfI_IsqAAAAAI6klpIc6PmZ6iIOHqG-xflHDVNt"></div>
                      </div>
                  @endif

                  <button class="btn btn-primary btn-lg btn-block" type="submit">Login</button><br>

                  <hr class="my-4">

                  <a href="{{ route('auth.google') }}" class="btn btn-danger btn-block">
                  <i class="fab fa-google"></i> Entrar amb Google
              </a>

              <a href="{{ route('auth.github') }}" class="login-with-github-btn">
                  <i class="fab fa-github"></i> Entrar amb GitHub
              </a>
              </form>
          </div>
        </div>
      </div>
    </div>  
  </div>
</section>
</body>
</html>
