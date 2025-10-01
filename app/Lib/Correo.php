<?php
namespace App\Lib;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Correo {
    private array $config;

    public function __construct(array $config) {
        $this->config = $config;
    }

    public function enviar_codigo(string $destinatario_email, string $codigo): bool {
        $mail = new PHPMailer(true);
        try {
            
            $mail->isSMTP();
            $mail->Host       = $this->config['servidor'];     // smtp-relay.brevo.com
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->config['usuario'];      // "apikey"
            $mail->Password   = $this->config['clave'];        // tu API key
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $this->config['puerto'];       // 587
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom($this->config['desde_email'], $this->config['desde_nombre']);
            $mail->addAddress($destinatario_email);
            $mail->Subject = 'Código de verificación';
            $mail->isHTML(true);
            $mail->Body = "
                <p>Usá este código para restablecer tu contraseña:</p>
                <h2 style='letter-spacing:3px;margin:10px 0'>{$codigo}</h2>
                <p>Vence en 15 minutos. Si no fuiste vos, ignorá este correo.</p>
            ";
            $mail->AltBody = "Código: {$codigo} (vence en 15 minutos)";

            error_log("INTENTO SMTP: from={$this->config['desde_email']} to=$destinatario_email host={$this->config['servidor']} port={$this->config['puerto']}");


            return $mail->send();
        } catch (Exception $e) {
            error_log('Error de correo: ' . $e->getMessage());
            return false;
        }
    }
}
