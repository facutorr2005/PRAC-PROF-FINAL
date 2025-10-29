<?php
namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer{
    
    public static function send(string $to, string $toName, string $subject, string $html): bool
    {
        $m = new PHPMailer(true);

        try {
            // Configuración SMTP de Brevo
            $m->isSMTP();
            $m->Host       = MAIL_HOST;                     // smtp-relay.brevo.com
            $m->Port       = MAIL_PORT;                     // 587
            $m->SMTPAuth   = true;
            $m->Username   = MAIL_USER;                     // tu usuario SMTP
            $m->Password   = MAIL_PASS;                     // tu clave SMTP
            $m->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $m->CharSet    = 'UTF-8';

            // Remitente y destinatario
            $m->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $m->addAddress($to, $toName);

            // Contenido
            $m->isHTML(true);
            $m->Subject = $subject;
            $m->Body    = $html;
            $m->AltBody = strip_tags($html);

            return $m->send();
        } catch (Exception $e) {
            error_log('Mailer error: ' . $e->getMessage());
            return false;
        }
    }
}


