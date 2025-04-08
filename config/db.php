<?php

// require_once __DIR__ . '/vendor/autoload.php';

// use Dotenv\Dotenv;

class Database {
    private $pdo;
    private static $instance = null;
    private $connection;

    private function __construct() {
        $host = getenv('DB_HOST');
        $dbname = getenv('DB_NAME');
        $username = getenv('DB_USER');
        $password = getenv('DB_PASS');
        
        try {
            $this->connection = new PDO(
                "mysql:host=$host;dbname=$dbname", 
                $username, 
                $password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->connection;
    }
    /**no borrar */
    private function loadEnv(string $filePath): void {
        if (!file_exists($filePath)) {
            throw new RuntimeException("Archivo .env no encontrado en: $filePath");
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue; // Saltar comentarios
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_ENV)) {
                $_ENV[$name] = $value;
                putenv("$name=$value");
            }
        }
    }
    /**
     * Insert a record into a table
     */
    public function insert(string $table, array $data): bool {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            throw new RuntimeException("Insert failed: " . $e->getMessage());
        }
    }

    /**
     * Update records in a table
     */
    public function update(string $table, array $data, string $where = '', array $whereParams = []): int {
        $set = [];
        foreach (array_keys($data) as $key) {
            $set[] = "$key = :$key";
        }
        $setClause = implode(', ', $set);
        
        $sql = "UPDATE $table SET $setClause";
        
        if (!empty($where)) {
            $sql .= " WHERE $where";
            $data = array_merge($data, $whereParams);
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($data);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new RuntimeException("Update failed: " . $e->getMessage());
        }
    }

    /**
     * Delete records from a table
     */
    public function delete(string $table, string $where = '', array $params = []): int {
        $sql = "DELETE FROM $table";
        
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new RuntimeException("Delete failed: " . $e->getMessage());
        }
    }

    /**
     * Select records with dynamic joins
     */
    public function select(array $tables, array $fields = ['*'], string $where = '', array $params = [], array $joins = []): array {
        // Build SELECT clause
        $selectFields = implode(', ', $fields);
        
        // Build FROM clause with joins
        $fromClause = $tables[0];
        foreach ($joins as $join) {
            $fromClause .= " {$join['type']} JOIN {$join['table']} ON {$join['condition']}";
        }
        
        $sql = "SELECT $selectFields FROM $fromClause";
        
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new RuntimeException("Select failed: " . $e->getMessage());
        }
    }

    /**
     * Execute a raw SQL query
     */
    public function query(string $sql, array $params = []): array {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new RuntimeException("Query failed: " . $e->getMessage());
        }
    }

    /**
     * Begin a transaction
     */
    public function beginTransaction(): bool {
        return $this->pdo->beginTransaction();
    }

    /**
     * Commit a transaction
     */
    public function commit(): bool {
        return $this->pdo->commit();
    }

    /**
     * Rollback a transaction
     */
    public function rollBack(): bool {
        return $this->pdo->rollBack();
    }

    /**
     * Get last inserted ID
     */
    public function lastInsertId(): string {
        return $this->pdo->lastInsertId();
    }

    /**
     * Get PDO instance
     */
    public function getPDO(): PDO {
        return $this->pdo;
    }
}