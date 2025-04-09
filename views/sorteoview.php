<?php
// var_dump(file_exists(__DIR__ . '/../controllers/sorteocontroller.php'));
require __DIR__ . '/../controllers/sorteocontroller.php';
// require __DIR__ . '/../controllers/premioscontroller.php';

$sorteoController = new SorteoController();
$sorteosActivos = $sorteoController->getActiveSorteos();

// $premiosController = new PremiosController();
// $premios = $premiosController->getPremiosSorteo($sorteosActivos['id']);
?>

<section class="sorteos-container">
    <div class="sorteos">
        <img src="resources/"<?php echo $sorteosActivos['FOTO']; ?> alt="Premios de Sorteo" class="sorteo-image">
    </div>
    <div>
        <?php echo $sorteosActivos['titulo']; ?>
    </div>
    <div>
        <?php echo $sorteosActivos['precio']; ?>
    </div>
</section>

<!-- Modal para compra de boletos -->
<div id="compraModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h3 id="modalSorteoTitulo"></h3>
        <div id="modalSorteoContent"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar clic en botones de compra
    document.querySelectorAll('.btn-comprar').forEach(btn => {
        btn.addEventListener('click', function() {
            const sorteoId = this.getAttribute('data-sorteo');
            cargarModalCompra(sorteoId);
        });
    });

    // Modal
    const modal = document.getElementById('compraModal');
    const span = document.querySelector('.close-modal');

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Cargar datos del sorteo en el modal
    function cargarModalCompra(sorteoId) {
        fetch(`/controller/sorteocontroller.php?action=getSorteo&id=${sorteoId}`)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    document.getElementById('modalSorteoTitulo').textContent = data.titulo;
                    
                    let modalContent = `
                        <p><strong>Fecha:</strong> ${new Date(data.fecha_sorteo).toLocaleDateString()}</p>
                        <p><strong>Números disponibles:</strong> ${data.qtynumeros - data.qtyvendidos}</p>
                        <div class="form-compra">
                            <label for="cantidad">Cantidad de boletos:</label>
                            <input type="number" id="cantidad" min="1" max="${data.qtynumeros - data.qtyvendidos}" value="1">
                            <button id="confirmarCompra" class="btn-confirmar">Confirmar Compra</button>
                        </div>
                    `;
                    
                    document.getElementById('modalSorteoContent').innerHTML = modalContent;
                    modal.style.display = "block";

                    // Manejar confirmación de compra
                    document.getElementById('confirmarCompra').addEventListener('click', function() {
                        const cantidad = document.getElementById('cantidad').value;
                        realizarCompra(sorteoId, cantidad);
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function realizarCompra(sorteoId, cantidad) {
        // Aquí iría la lógica para procesar la compra
        alert(`Compra de ${cantidad} boletos para el sorteo ${sorteoId} realizada`);
        modal.style.display = "none";
    }
});
</script>