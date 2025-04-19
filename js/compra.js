export const setupCompra = () => {
    const registroForm = document.getElementById('registroClienteForm');
    if (!registroForm) {
        console.error('Formulario no encontrado');
        return;
    }

    registroForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        console.log('Evento submit capturado');
        
        // Validar campos
        const requiredFields = ['nombre', 'apellido', 'numIdentificacion', 'pais', 'email', 'telefono'];
        let isValid = true;

        requiredFields.forEach(field => {
            const input = document.getElementById(field);
            console.log(`Validando campo ${field}:`, input);
            
            if (!input || !input.value.trim()) {
                if (input) input.style.border = '1px solid red';
                isValid = false;
            } else if (input) {
                input.style.border = '';
            }
        });

        const isAdult = document.getElementById('is_adult')?.checked;
        if (!isAdult) {
            alert('Debes ser mayor de edad para participar');
            return;
        }

        if (!isValid) {
            alert('Por favor completa todos los campos requeridos');
            return;
        }

        // Crear FormData y agregar todos los campos
        const formData = new FormData(registroForm);
        formData.append('action', 'registrarCompra');
        formData.append('numeros', JSON.stringify(window.numerosSeleccionados || []));
        formData.append('sorteo_id', document.querySelector('.btn-comprar')?.getAttribute('data-sorteo') || '');
        formData.append('precio', window.appData?.precioNumero || 0);
        formData.append('is_adult', document.getElementById('is_adult').checked ? '1' : '0');

        console.log('Datos a enviar:', Object.fromEntries(formData));

        try {
            // Enviar datos al servidor
            const response = await fetch('index.php', {
                method: 'POST',
                body: formData // Deja que el navegador establezca el Content-Type
            });

            console.log('Respuesta recibida:', response);

            // Manejar la respuesta como texto primero para debug
            const responseText = await response.text();
            console.log('Contenido de la respuesta:', responseText);

            try {
                const result = JSON.parse(responseText);
                
                if (!response.ok) {
                    throw new Error(result.message || 'Error en la respuesta del servidor');
                }

                if (result.success) {
                    alert(result.message);
                    window.location.reload();
                } else {
                    throw new Error(result.message || 'Ocurrió un error al procesar tu compra');
                }
            } catch (e) {
                console.error('Error parseando JSON:', e);
                throw new Error('Respuesta inesperada del servidor: ' + responseText.substring(0, 100));
            }
        } catch (error) {
            console.error('Error:', error);
            alert(error.message || 'Ocurrió un error al procesar tu compra. Por favor intenta nuevamente.');
        }
    });
};