<?php

require "./bibliotecas/PHPMailer/Exception.php";
require "./bibliotecas/PHPMailer/OAuth.php";
require "./bibliotecas/PHPMailer/PHPMailer.php";
require "./bibliotecas/PHPMailer/POP3.php";
require "./bibliotecas/PHPMailer/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Mensagem {

    private $para     = null;
    private $assunto  = null;
    private $mensagem = null;

    public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function mensagemValida() {

        if( empty($this->para) || empty($this->assunto) || empty($this->mensagem) ) {
            return false;
        }

        return true;

    }

}

$mensagem = new Mensagem();

$mensagem->__set("para", $_POST["para"]);
$mensagem->__set("assunto", $_POST["assunto"]);
$mensagem->__set("mensagem", $_POST["mensagem"]);

if( !$mensagem->mensagemValida() ) {
    echo "Mensagem não é valida";
    die();
}

//Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'ad.secre.boavista@gmail.com';
    $mail->Password   = 'B0@vista';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    //Recipients
    $mail->setFrom('ad.secre.boavista@gmail.com', 'Boa vista');
    $mail->addAddress('benesinho14@gmail.com', 'Fernando');
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Oi';
    $mail->Body    = 'Este é um email de teste com <strong>Send Mail</strong>';
    $mail->AltBody = 'Este é um email de teste com Send Mail';

    $mail->send();
    echo 'Mensagem enviada com sucesso!';
} catch (Exception $e) {
    echo "Não foi possíel enviar esse e-mail, Por favor tente novamente mais tarde: {$mail->ErrorInfo}";
}


?>