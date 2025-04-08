<?php

class Controller {
    private static $instances = [];
    private $config;
    private $services = [];

    public function __construct(array $config = []) {
        $this->config = $config;
        $this->initialize();
    }

    /**
     * Inicializa los servicios básicos
     */
    private function initialize(): void {
        // Cargar configuración de entorno
        $this->loadConfig();
        
        // Registrar servicios básicos
        $this->register('database', function() {
            return new Database($this->config['database'] ?? []);
        });
    }

    /**
     * Carga la configuración desde diferentes fuentes
     */
    private function loadConfig(): void {
        // Cargar configuración desde archivo si existe
        if (file_exists(__DIR__.'/config.php')) {
            $this->config = array_merge($this->config, require __DIR__.'/config.php');
        }
        
        // Cargar variables de entorno
        $this->loadEnv(__DIR__ . '/../.env');
    }

    /**
     * Carga variables de entorno manualmente
     */
    private function loadEnv(string $filePath): void {
        if (!file_exists($filePath)) return;

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            
            if (!array_key_exists($name, $_ENV)) {
                $_ENV[$name] = $value;
                $this->config[$name] = $value;
            }
        }
    }

    /**
     * Registra un servicio para creación lazy
     */
    public function register(string $name, callable $resolver): void {
        $this->services[$name] = $resolver;
    }

    /**
     * Obtiene una instancia de servicio/clase
     */
    public function get(string $name) {
        // Verificar si ya existe una instancia
        if (isset(self::$instances[$name])) {
            return self::$instances[$name];
        }

        // Verificar si es un servicio registrado
        if (isset($this->services[$name])) {
            self::$instances[$name] = $this->services[$name]();
            return self::$instances[$name];
        }

        // Intenta cargar una clase automáticamente
        $className = ucfirst($name);
        if (class_exists($className)) {
            self::$instances[$name] = new $className($this);
            return self::$instances[$name];
        }

        throw new RuntimeException("Servicio o clase no encontrada: $name");
    }

    /**
     * Método mágico para acceder a servicios como propiedades
     */
    public function __get(string $name) {
        return $this->get($name);
    }

    /**
     * Método mágico para verificar existencia de servicios
     */
    public function __isset(string $name): bool {
        return isset($this->services[$name]) || class_exists(ucfirst($name));
    }

    /**
     * Obtiene configuración
     */
    public function config(string $key, $default = null) {
        return $this->config[$key] ?? $default;
    }

    /**
     * Singleton: Obtiene la instancia única del controlador
     */
    public static function getInstance(array $config = []): self {
        if (!isset(self::$instances['app'])) {
            self::$instances['app'] = new self($config);
        }
        return self::$instances['app'];
    }
}