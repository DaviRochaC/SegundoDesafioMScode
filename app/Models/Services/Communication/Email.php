<?php

namespace App\Models\Services\Communication;
use PHPMailer\PHPMailer\{PHPMailer, Exception};
use Dotenv\Dotenv;



class Email
{

    private $mail;

    /**
     * Função para enviar E-mail.
     * @param string $destinatario Para quem o e-mail será enviado.
     * @param string $assunto Assunto do e-mail.
     * @param string $conteudo O conteudo que o email irá apresentar.
     */
    public function enviarEmail(string $destinatario, string $assunto, string $conteudoHtml)
    {

        $dotenv = Dotenv::createUnsafeMutable('/opt/lampp/htdocs/mscode/challengetwo/');


        $dotenv->load();

        $this->mail = new PHPMailer(true);

        try {

            $email = getenv('EMAIL');
            $senha = getenv('SENHA');
            $this->mail->isSMTP();

            //config para se autenticar no SMTP

            $this->mail->Host = ' smtp.gmail.com.';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = $email;
            $this->mail->Password = $senha;
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = 587;

            //info do remetente
            $this->mail->setFrom($email, 'Graphic');
            $this->mail->addReplyTo($email, 'Graphic');

            //info do Destinatario
            $this->mail->addAddress($destinatario);  // PARA QUEM VOU ENVIAR
            $this->mail->isHTML(true); //corpo da mensagem aceita HMTL
            $this->mail->Subject = utf8_decode($assunto);
            $this->mail->Body = utf8_decode("<html><body>$conteudoHtml</body></html>");

            //envia email
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return ('Erro ao enviar: ' . $e->getMessage());
        }
    }
}
