@include('navbar_view')

<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="utf-8" />
    <title>Modificar escollir</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('CSS/estils.css') }}">
</head>

<body>
    <form method="POST" action="{{ url('/modificar_controlador') }}">
        @csrf
        <h2>
            <p class="titol">Selecciona el camp a modificar</p><br>
            
            <!-- Camp per introduir l'ID -->
            <div class="c-formContainer">
                <input type="text" class="boton-id" name="id" placeholder="ID" 
                       value="{{ session('id') ?? '' }}" />
            </div>
            <br>

            <!-- Switch per Títol -->
            <div class="checkbox-wrapper-22">
                <label class="switch" for="check-titol">
                    <input type="radio" id="check-titol" name="field" value="titol" required>
                    <div class="slider round"></div>
                </label>
                <span class="titol-chulo">Títol</span>
            </div>
            

            <!-- Switch per Cos -->
            <div class="checkbox-wrapper-22">
                <label class="switch" for="check-cos">
                    <input type="radio" id="check-cos" name="field" value="cos" required>
                    <div class="slider round"></div>
                </label>
                <span class="titol-chulo">Cos</span>
            </div>
            <br>

            <input type="submit" value="Seleccionar" class="boto"><br>

            <br>

            @if (session('missatge_exit'))
                <p style="color: #2ee20e; font-family: 'Calligraffitti', cursive;">{{ session('missatge_exit') }}</p>
            @elseif (session('missatge'))
                <p style="color: red; font-family: 'Calligraffitti', cursive;">{{ session('missatge') }}</p>
            @endif

        </h2>
    </form>

    <div>
        <a href="{{ url('/index_usuari') }}">
            <button type="button" class="tornar" role="button">Anar enrere</button>
        </a>
    </div>
</body>
</html>