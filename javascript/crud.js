// Función genérica para cargar datos desde cualquier entidad
function loadEntity(entity, tableId) {
    fetch(`..crud/${entity}.php?action=getAll`, { method: 'GET' }) // Se espera que PHP soporte esta acción
        .then(response => response.text()) // PHP devuelve texto (como HTML o tabla renderizada)
        .then(data => {
            const tableBody = document.querySelector(`#${tableId} tbody`);
            tableBody.innerHTML = data; // Actualiza directamente el contenido de la tabla
        })
        .catch(error => {
            console.error(`Error al cargar ${entity}:`, error);
            alert(`Ocurrió un error al cargar la lista de ${entity}.`);
        });
}

// Función para iniciar el proceso de edición
function editEntity(entity, id) {
    const formData = new FormData();
    formData.append('action', 'get');
    formData.append('id', id);

    fetch(`..crud/${entity}.php`, {
        method: 'POST',
        body: formData,
    })
        .then(response => response.text()) // PHP devuelve un formulario pre-rellenado
        .then(data => {
            const formContainer = document.querySelector('#formModal');
            formContainer.innerHTML = data; // Inserta el formulario en el modal
            formContainer.style.display = 'block'; // Muestra el modal
        })
        .catch(error => {
            console.error(`Error al obtener datos de ${entity}:`, error);
            alert(`Ocurrió un error al cargar los datos para editar.`);
        });
}

// Función para manejar el envío del formulario (crear/editar)
function submitEntityForm(event, entity, tableId) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);

    fetch(`..crud/${entity}.php`, {
        method: 'POST',
        body: formData,
    })
        .then(response => response.text())
        .then(result => {
            alert(result.includes("error") ? `Error: ${result}` : 'Operación realizada exitosamente');
            loadEntity(entity, tableId); // Recarga la tabla
            document.querySelector('#formModal').style.display = 'none'; // Oculta el modal
        })
        .catch(error => {
            console.error(`Error al procesar ${entity}:`, error);
            alert('Ocurrió un error al procesar la solicitud.');
        });
}

// Función para eliminar un registro
function deleteEntity(entity, id, tableId) {
    if (!confirm('¿Estás seguro de que deseas eliminar este registro?')) return;

    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('id', id);

    fetch(`..crud/${entity}.php`, {
        method: 'POST',
        body: formData,
    })
        .then(response => response.text())
        .then(result => {
            alert(result.includes("error") ? `Error: ${result}` : 'Registro eliminado correctamente');
            loadEntity(entity, tableId); // Recarga la tabla después de la eliminación
        })
        .catch(error => {
            console.error(`Error al eliminar en ${entity}:`, error);
            alert('Ocurrió un error al eliminar el registro.');
        });
}

// Carga inicial de todas las entidades (ajusta según tu estructura)
document.addEventListener('DOMContentLoaded', () => {
    const entities = ['actor', 'butacas', 'cine', 'cliente', 'empleados',
        'entrada', 'factura_cabecera', 'factura_detalles', 'funcion', 
        'genero', 'pelicula', 'personaje', 'salas'
    ]; // Ajusta con tus entidades
    entities.forEach(entity => loadEntity(entity, `${entity}Table`));
});
