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
            // Función para cargar los artículos de forma automática
            function carregarArticles() {
                fetch('{{ url("/controladorAjax") }}?action=obtenir_articles')
                    .then(response => {
                        if (!response.ok) throw new Error("Error en la resposta del servidor.");
                        return response.json();
                    })
                    .then(articles => {
                        const tbody = document.querySelector(".fl-table tbody");
                        tbody.innerHTML = ""; // Limpia el contenido actual de la tabla
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

            // Cargar los artículos automáticamente al cargar la página
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
                    <!-- Aquí se cargarán los datos mediante JavaScript -->
                </tbody>
            </table>
        </div>

        <div>
            <a href="{{ url('/index_usuari') }}">
                <button type="button" class="tornar" role="button">Anar enrere</button>
            </a>
        </div>
    </div>
</body>
</html>