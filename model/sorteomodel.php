<?php
require_once __DIR__ . '/../config/db.php';

class SorteoModel {
    private $db;

    public function __construct() {
        $this->db = new Database(); // Asumo que tienes una clase Database en db.php
    }

    /**
     * Obtiene todos los sorteos
     * @return array
     */
    public function getAllSorteos() {
        $query = "SELECT * FROM sorteo";
        return $this->db->query($query);
    }

    /**
     * Obtiene un sorteo por ID
     * @param int $id
     * @return array|null
     */
    public function getSorteoById($id) {
        $query = "SELECT * FROM sorteo WHERE id = :id";
        $params = [':id' => $id];
        $result = $this->db->query($query, $params);
        return $result[0] ?? null;
    }

    /**
     * Crea un nuevo sorteo
     * @param array $data
     * @return int ID del sorteo creado
     */
    public function createSorteo($data) {
        $query = "INSERT INTO sorteo (fecha_iniciovtas, fecha_sorteo, hora, nrosorteo, qtynumeros, 
                  qtyvendidos, titulo, resultado, observacion, reglas, status) 
                  VALUES (:fecha_iniciovtas, :fecha_sorteo, :hora, :nrosorteo, :qtynumeros, 
                  :qtyvendidos, :titulo, :resultado, :observacion, :reglas, :status)";
        
        $params = [
            ':fecha_iniciovtas' => $data['fecha_iniciovtas'],
            ':fecha_sorteo' => $data['fecha_sorteo'],
            ':hora' => $data['hora'],
            ':nrosorteo' => $data['nrosorteo'],
            ':qtynumeros' => $data['qtynumeros'],
            ':qtyvendidos' => $data['qtyvendidos'],
            ':titulo' => $data['titulo'],
            ':resultado' => $data['resultado'],
            ':observacion' => $data['observacion'],
            ':reglas' => $data['reglas'],
            ':status' => $data['status']
        ];
        
        $this->db->execute($query, $params);
        return $this->db->lastInsertId();
    }

    /**
     * Actualiza un sorteo existente
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateSorteo($id, $data) {
        $query = "UPDATE sorteo SET 
                  fecha_iniciovtas = :fecha_iniciovtas, 
                  fecha_sorteo = :fecha_sorteo, 
                  hora = :hora, 
                  nrosorteo = :nrosorteo, 
                  qtynumeros = :qtynumeros, 
                  qtyvendidos = :qtyvendidos, 
                  titulo = :titulo, 
                  resultado = :resultado, 
                  observacion = :observacion, 
                  reglas = :reglas, 
                  status = :status 
                  WHERE id = :id";
        
        $params = [
            ':id' => $id,
            ':fecha_iniciovtas' => $data['fecha_iniciovtas'],
            ':fecha_sorteo' => $data['fecha_sorteo'],
            ':hora' => $data['hora'],
            ':nrosorteo' => $data['nrosorteo'],
            ':qtynumeros' => $data['qtynumeros'],
            ':qtyvendidos' => $data['qtyvendidos'],
            ':titulo' => $data['titulo'],
            ':resultado' => $data['resultado'],
            ':observacion' => $data['observacion'],
            ':reglas' => $data['reglas'],
            ':status' => $data['status']
        ];
        
        return $this->db->execute($query, $params);
    }

    /**
     * Elimina un sorteo
     * @param int $id
     * @return bool
     */
    public function deleteSorteo($id) {
        $query = "DELETE FROM sorteo WHERE id = :id";
        $params = [':id' => $id];
        return $this->db->execute($query, $params);
    }

    /**
     * Obtiene sorteos activos
     * @return array
     */
    public function getActiveSorteos() {
        $query = "SELECT * FROM sorteo WHERE status = 'A'";
        return $this->db->query($query);
    }
}
?>