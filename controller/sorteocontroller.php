<?php
require_once __DIR__ . '/../model/sorteomodel.php';

class SorteoController {
    private $model;

    public function __construct() {
        $this->model = new SorteoModel();
    }

    /**
     * Obtiene todos los sorteos
     * @return array
     */
    public function getAllSorteos() {
        return $this->model->getAllSorteos();
    }

    /**
     * Obtiene un sorteo por ID
     * @param int $id
     * @return array|null
     */
    public function getSorteo($id) {
        return $this->model->getSorteoById($id);
    }

    /**
     * Crea un nuevo sorteo
     * @param array $data
     * @return array
     */
    public function createSorteo($data) {
        try {
            $id = $this->model->createSorteo($data);
            return ['success' => true, 'id' => $id];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Actualiza un sorteo existente
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updateSorteo($id, $data) {
        try {
            $success = $this->model->updateSorteo($id, $data);
            return ['success' => $success];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Elimina un sorteo
     * @param int $id
     * @return array
     */
    public function deleteSorteo($id) {
        try {
            $success = $this->model->deleteSorteo($id);
            return ['success' => $success];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Obtiene sorteos activos
     * @return array
     */
    public function getActiveSorteos() {
        return $this->model->getActiveSorteos();
    }

    /**
     * Valida los datos del sorteo antes de crear/actualizar
     * @param array $data
     * @return array
     */
    public function validateSorteoData($data) {
        $errors = [];

        if (empty($data['titulo'])) {
            $errors[] = 'El título es requerido';
        }

        if (empty($data['fecha_sorteo'])) {
            $errors[] = 'La fecha de sorteo es requerida';
        }

        if (empty($data['nrosorteo'])) {
            $errors[] = 'El número de sorteo es requerido';
        }

        return $errors;
    }
}
?>