@php
    use Illuminate\Support\Facades\Session;
    use App\Models\Usuari;

    $usuari = session('usuari') ?? 'Invitat';
    $dadesUsuari = Usuari::where('usuari', $usuari)->first();
    $imatgePerfil = $dadesUsuari?->imatge ?? asset('Imatges/def_user.jpeg');
@endphp

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <nav class="navbar bg-body-tertiary">
      <div class="container">
        <a class="navbar-brand">
          <img src="{{ asset($imatgePerfil) }}" alt="Avatar" class="rounded-circle" width="50" height="50">
        </a>
      </div>
    </nav>
    <a class="navbar-brand">Benvingut {{ $usuari }}</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul>

      <form method="POST" action="{{ route('logout') }}" class="d-flex ms-auto" role="search">
        @csrf
        <div class="btn-group">
          <button class="btn btn-secondary btn-lg dropdown-toggle mx-5" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{ $usuari }}
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ url('modificar_perfil') }}">Modificar perfil</a></li>
            @if ($dadesUsuari && $dadesUsuari->aut_social === 'no')
              <li><a class="dropdown-item" href="{{ url('modificar_contrasenya') }}">Canvi de contrasenya</a></li>
            @endif
            <li><a class="dropdown-item" href="{{ url('vistaAjax') }}">Articles compartits</a></li>
            <li><a class="dropdown-item" href="{{ url('lectura_qr') }}">Lectura QR</a></li>
            <li><a class="dropdown-item" href="{{ url('lectura_api') }}">Lectura API</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><button type="submit" class="dropdown-item">Logout</button></li>
          </ul>
        </div>
      </form>
    </div>
  </div>
</nav>
