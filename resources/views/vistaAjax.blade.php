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
    <title>Articles Compartits</title>
    <link rel="stylesheet" href="{{ asset('CSS/estils.css') }}">

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Funció per carregar els articles de forma automàtica
            function carregarArticles() {
                fetch('{{ url("/controladorAjax") }}?action=obtenir_articles')
                    .then(response => {
                        if (!response.ok) throw new Error("Error en la resposta del servidor.");
                        return response.json();
                    })
                    .then(articles => {
                        const tbody = document.querySelector(".fl-table tbody");
                        tbody.innerHTML = ""; // Neteja el contingut actual de la taula
                        articles.forEach(article => {
                            const row = document.createElement("tr");
                            row.innerHTML = `
                                <td>${article.usuari}</td>
                                <td>${article.titol}</td>
                                <td>${article.cos}</td>
                                <td>${article.font_origen}</td>
                                <td>
                                    <a href="{{ url('/copiarAjax') }}?article_id=${article.id}">
                                        <img src="{{ asset('Imatges/copiar.png') }}" alt="Copiar" style="width:24px; height:24px;">
                                    </a>
                                </td>
                            `;
                            tbody.appendChild(row);
                        });
                    })
                    .catch(error => console.error("Error carregant els articles:", error));
            }

            // Carregar els articles automàticament en carregar la pàgina
            carregarArticles();
        });
    </script>
</head>
<body>
    <div class="container"><br><br>
    <h3 class="titol_canvi_pass">Articles Compartits</h3>
        @if(session('missatge_exit'))
            <p style='color: green; font-family: "Calligraffitti", cursive;'>{{ session('missatge_exit') }}</p>
        @elseif(session('missatge'))
            <p style='color: red; font-family: "Calligraffitti", cursive;'>{{ session('missatge') }}</p>
        @endif

        <div class='table-wrapper'>
            <table class='fl-table'>
                <thead>
                    <tr>
                        <th>Usuari</th>
                        <th>Títol</th>
                        <th>Cos</th>
                        <th>Font</th>
                        <th>Acció</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí es carregaran les dades mitjançant JavaScript -->
                </tbody>
            </table>
        </div>

        <div>
            <a href="{{ url('/index_usuari') }}">
                <button type="button" class="tornar" role="button">Anar enrere</button>
            </a>
        </div>
    </div>

    <div class="mb-3">
        <form action="{{ route('processar_qr') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card mt-4">
                <div class="card-header bg-dark text-white">
                    <h4>Pujar QR</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="qr_file" class="form-label">Selecciona un arxiu QR</label>
                        <input type="file" name="qr_file" id="qr_file" class="form-control" accept=".png, .jpg, .jpeg">
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Llegir QR</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>