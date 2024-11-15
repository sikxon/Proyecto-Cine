document.querySelector('#crudForm').addEventListener('submit', (event) => {
    event.preventDefault();
    const action = document.querySelector('[name="action"]').value;
    const entity = document.querySelector('[name="entity"]').value;
    const data = {};

    new FormData(event.target).forEach((value, key) => {
        data[key] = value;
    });

    fetch(`entidades/${entity}.php`, {
        method: 'POST',
        body: JSON.stringify({ action, data }),
        headers: { 'Content-Type': 'application/json' }
    })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                alert('Operación realizada correctamente');
            } else {
                alert('Error: ' + result.message);
            }
        });
});
