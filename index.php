<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cine</title>
    <link rel="stylesheet" href="styles/styles.css">
    <script src="scripts/navigation.js" defer></script>
</head>
<body>
    <header>
        <nav>
            <ul id="navbar">
                <li data-entity="cliente">Cliente</li>
                <li data-entity="entrada">Entrada</li>
                <li data-entity="factura_detalles">Factura Detalles</li>
                <li data-entity="factura_cabecera">Factura Cabecera</li>
                <li data-entity="butacas">Butacas</li>
                <li data-entity="salas">Salas</li>
                <li data-entity="cines">Cines</li>
                <li data-entity="funcion">Función</li>
                <li data-entity="personaje">Personaje</li>
                <li data-entity="actor">Actor</li>
                <li data-entity="empleados">Empleados</li>
            </ul>
        </nav>
    </header>

    <main id="main-content">
        <!-- Contenido dinámico cargado aquí -->
        <h2>Bienvenido al sistema de gestión de cine</h2>
        <p>Seleccione una entidad o consulta en la barra superior.</p>
    </main>

    <script>
        const navbar = document.getElementById('navbar');
        const mainContent = document.getElementById('main-content');

        navbar.addEventListener('click', (event) => {
            const entity = event.target.getAttribute('data-entity');
            if (entity) {
                // Cargar contenido dinámico para la entidad seleccionada
                fetch(`crud/${entity}.php`) // Cambié la ruta para que sea válida.
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.text();
                    })
                    .then(html => {
                        mainContent.innerHTML = html;
                    })
                    .catch(error => {
                        mainContent.innerHTML = `<p>Error cargando la entidad: ${entity}. Por favor, verifica que el archivo existe.</p>`;
                        console.error(error);
                    });
            }
        });
    </script>
</body>
</html>
