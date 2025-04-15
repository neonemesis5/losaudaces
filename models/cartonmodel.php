<?php
use Core\BaseModel;

require_once __DIR__ . '/../core/basemodel.php';
require_once __DIR__ . '/../core/Database.php';

class CartonModel extends BaseModel {  // Nombre de clase con mayúscula
    public function __construct() {
        parent::__construct();  // Primero llamar al padre
        $this->table = 'carton';
    }
    public function getAll(): array {
        return $this->query(
            "SELECT * FROM {$this->table} ORDER BY id ASC"  // Cambiado a 'posicion' para consistencia
        );
    }
    /**
     * Obtiene las celdas asociadas a un sorteo específico.
     *
     * @param int $sorteoId ID del sorteo
     * @return array Lista de celdas co n sus IDs y nombres
     */
    public function getCartonSellBySorteo($sorteoId): array {
        return $this->query(
            "SELECT id,numero FROM {$this->table} WHERE sorteo_id = :sorteo_id and status=:st ORDER BY numero ASC",
            ['sorteo_id' => $sorteoId, 'st' => 'V']  // Parámetro preparado
        );
    }
}