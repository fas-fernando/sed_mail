<?php

require "./bibliotecas/PHPMailer/Exception.php";
require "./bibliotecas/PHPMailer/OAuth.php";
require "./bibliotecas/PHPMailer/PHPMailer.php";
require "./bibliotecas/PHPMailer/POP3.php";
require "./bibliotecas/PHPMailer/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Mensagem
{

    private $para     = null;
    private $assunto  = null;
    private $mensagem = null;
    public  $status   = array("cod_status" => null, "desc_status" => "");

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function mensagemValida()
    {

        if (empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
            return false;
        }

        return true;
    }
}

$mensagem = new Mensagem();

$mensagem->__set("para", $_POST["para"]);
$mensagem->__set("assunto", $_POST["assunto"]);
$mensagem->__set("mensagem", $_POST["mensagem"]);

if (!$mensagem->mensagemValida()) {

    echo "Mensagem não é valida";
    header("Location: index.php");
}

//Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {

    //Server settings
    $mail->SMTPDebug = false;
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'ad.secre.boavista@gmail.com';
    $mail->Password   = 'B0@vista';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    //Recipients
    $mail->setFrom('ad.secre.boavista@gmail.com', 'Boa vista');
    $mail->addAddress($mensagem->__get("para"));
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');

    //Content
    $mail->isHTML(true);
    $mail->Subject = $mensagem->__get("assunto");
    $mail->Body    = $mensagem->__get("mensagem");
    $mail->AltBody = 'É necessário usar um client email HTML para ter acesso total ao conteúdo dessa mensagem';

    $mail->send();

    $mensagem->status["cod_status"] = 1;
    $mensagem->status["desc_status"] = "Mensagem enviada com sucesso!";
} catch (Exception $e) {

    $mensagem->status["cod_status"] = 2;
    $mensagem->status["desc_status"] = "Não foi possíel enviar esse e-mail, Por favor tente novamente mais tarde. Detalhes do erro: {$mail->ErrorInfo}";
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="utf-8" />
    <title>App Mail Send</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>

<body>

    <div class="container">
        <div class="py-3 text-center">
            <img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
            <h2>Send Mail</h2>
            <p class="lead">Seu app de envio de e-mails particular!</p>
        </div>

        <div class="row">
            <div class="col-md-12">

                <?php if ($mensagem->status["cod_status"] == 1) : ?>

                    <div class="container">
                        <h1 class="display-4 text-success">Sucesso</h1>
                        <p><?= $mensagem->status["desc_status"] ?></p>
                        <a href="index.php" class="btn btn-success mt-5">Voltar</a>
                    </div>

                <?php else : ?>

                    <div class="container">
                        <h1 class="display-4 text-danger">Ops!</h1>
                        <p><?= $mensagem->status["desc_status"] ?></p>
                        <a href="index.php" class="btn btn-success mt-5">Voltar</a>
                    </div>

                <?php endif; ?>

            </div>
        </div>
    </div>

</body>

</html>