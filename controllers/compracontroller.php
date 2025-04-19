<?php

use Core\BaseController;

require_once __DIR__ . '/../models/personamodel.php';
require_once __DIR__ . '/../models/cartonmodel.php';
require_once __DIR__ . '/../core/basecontroller.php';

class CompraController extends BaseController
{
    protected $personaModel;
    protected $cartonModel;

    public function __construct()
    {
        parent::__construct();
        $this->personaModel = new PersonaModel();
        $this->cartonModel = new CartonModel();
    }

    public function registrarCompra()
    {
        header('Content-Type: application/json');
    
        try {
            // Usar $_POST directamente ya que ahora usamos FormData
            $data = $_POST;
            
            // Convertir el campo 'numeros' de JSON a array
            if (!empty($data['numeros'])) {
                $data['numeros'] = json_decode($data['numeros'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception("Formato inválido para los números de compra");
                }
            }
    
            // Validar datos recibidos
            $requiredFields = ['nombre', 'apellido', 'numIdentificacion', 'pais', 'email', 'telefono', 'numeros', 'sorteo_id'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    throw new Exception("El campo $field es requerido");
                }
            }
    
            if (empty($data['is_adult']) || $data['is_adult'] !== '1') {
                throw new Exception("Debes ser mayor de edad para participar");
            }
    
            // Crear persona (manteniendo tu lógica original)
            $personaData = [
                'location_id' => (int)$data['pais'],
                'name' => $data['nombre'],
                'lastname' => $data['apellido'],
                'telefonos' => $data['telefono'],
                'email' => $data['email'],
                'nrocedula' => $data['numIdentificacion'],
                'login' => $data['email'],
                'password' => password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT),
                'validado' => '1',
                'status' => 'A'
            ];
    
            $personaId = $this->personaModel->createPersona($personaData);
            if (!$personaId) {
                throw new Exception("Error al registrar el cliente");
            }
    
            // Registrar números comprados (no necesitamos json_decode nuevamente ya que ya lo convertimos)
            $numeros = $data['numeros'];
            $sorteoId = (int)$data['sorteo_id'];
            $precio = (float)$data['precio'];
            $ip = $_SERVER['REMOTE_ADDR'];
            $fecha = date('Y-m-d H:i:s');
    
            foreach ($numeros as $numero) {
                $cartonData = [
                    'persona_id' => $personaId,
                    'sorteo_id' => $sorteoId,
                    'fechacompra' => $fecha,
                    'numero' => (int)$numero,
                    'ip' => $ip,
                    'precio' => $precio,
                    'status' => 'V' // Vendido
                ];
    
                $this->cartonModel->insert($cartonData);
            }
    
            // Enviar email de confirmación (manteniendo tu método original)
            $this->enviarEmailConfirmacion($personaData, $numeros, $sorteoId, $precio);
    
            echo json_encode([
                'success' => true,
                'message' => 'Registro y compra realizados con éxito. Se ha enviado un email de confirmación.'
            ]);
    
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function enviarEmailConfirmacion($personaData, $numeros, $sorteoId, $precioTotal)
    {
        $to = $personaData['email'];
        $subject = "Confirmación de compra - Los Audaces";

        $numerosStr = implode(', ', $numeros);
        $total = number_format($precioTotal * count($numeros), 2);

        $message = "
        <html>
        <head>
            <title>Confirmación de compra</title>
        </head>
        <body>
            <h2>¡Gracias por tu compra, {$personaData['name']}!</h2>
            <p>Has comprado los siguientes números para el sorteo #$sorteoId:</p>
            <p><strong>Números:</strong> $numerosStr</p>
            <p><strong>Total pagado:</strong> $$total</p>
            <p>Te deseamos mucha suerte en el sorteo.</p>
            <p>Atentamente,</p>
            <p>El equipo de Los Audaces</p>
        </body>
        </html>
        ";

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: Los Audaces <no-reply@losaudaces.com>\r\n";

        mail($to, $subject, $message, $headers);
    }
}
