// Generación de tabla de números
export const setupTicketsTable = (totalNumeros, numerosVendidos) => {
    function generarTablaNumeros(start, end) {
        const tabla = document.querySelector('.numeros-tabla');
        tabla.innerHTML = '';
        let num = start;
        
        for (let i = 0; i < 10; i++) {
            const row = document.createElement('tr');
            for (let j = 0; j < 10; j++) {
                if (num <= end && num < totalNumeros) {
                    const numeroFormateado = String(num).padStart(3, '0');
                    const estaVendido = numerosVendidos.includes(numeroFormateado);

                    const cell = document.createElement('td');
                    const link = document.createElement('a');
                    link.href = estaVendido ? 'javascript:void(0)' : '#';
                    const div = document.createElement('div');
                    div.className = 'carton-numero ' + (estaVendido ? 'vendido' : 'disponible');
                    div.textContent = numeroFormateado;

                    if (estaVendido) {
                        div.title = 'Número ya vendido';
                    } else {
                        div.addEventListener('click', function() {
							// Aquí puedes agregar la lógica para seleccionar el número
                            console.log('Número seleccionado:', numeroFormateado);
                        });
                    }
                    link.appendChild(div);
                    cell.appendChild(link);
                    row.appendChild(cell);
                    num++;
                }
            }
            tabla.appendChild(row);
        }
    }

    // Eventos para botones de rango
    document.querySelectorAll('.rango-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const start = parseInt(this.getAttribute('data-start'));
            const end = parseInt(this.getAttribute('data-end'));
            generarTablaNumeros(start, end);
        });
    });

    // Tabla inicial
    generarTablaNumeros(0, 99);
};

// Google Maps
let mapLoaded = false;

export const initMap = () => {
    const ubicacion = { lat: 7.8891, lng: -72.5078 };
    const map = new google.maps.Map(document.getElementById("mapa"), {
        zoom: 16,
        center: ubicacion,
    });
    new google.maps.Marker({
        position: ubicacion,
        map: map,
        title: "Los Audaces",
    });
};

export const loadGoogleMaps = () => {
    if (mapLoaded) return;
    mapLoaded = true;
    // El script de Google Maps ya se carga desde el HTML
};