<?php
class Model {
    protected $db;
    protected $table;
    
    public function __construct() {
        require_once __DIR__ . '/../config/db.php';
        $this->db = Database::getInstance();
    }
    
    public function all() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Otros métodos comunes como create, update, delete, etc.
}