<?php
require_once(ABSPATH.'/app/require/classes/Phpmailer.class.php');
/*
responsavel por gerenciar o envio do e-mail

@developer		Gabriel Estival
@create_code	28/08/2019
@last_update	28/08/2019
@version        0.1

@param array 	$destino		São as pessoas que receberao esse email
@param string 	$assunto		É o assunto do e-mail
@param string	$mensagem		Corpo HTML da mensagem
@param bollean	$isSMTP			Defini se o e-mail será enviado de forma autenticada ou não.
@param string	$enviadopor		Defini o nome que aparecerá o e-mail
@param string	$replay_mail	Defini qual o e-mail que receberá o replay
@param string	$replay_name	Defini qual o nome da pessoal que recebera o replay

@param string   $smtp           Defini o endereço smtp
@param string   $smtp_email     Defini o e-mail que será autenticado
@param string   $stmp_senha     Defini a senha do e-mail que será autenticado
@param strign   $stmp_pot       Defini a porta de envio dos e-mails
*/
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
class ProSendermail{

	public $destino;
    public $assunto;
    public $mensagem;
    public $isSMTP          = 1;
    public $enviadopor;
    public $replay_mail;
    public $replay_name;

    public $smtp            = SMTP;
    public $smtp_email      = EMAIL;
    public $smtp_senha      = PWD_EMAIL;
    public $smtp_port       = PORT_SMTP;


    //inicializo o metodo construtor;
    public function __construct(){
        return;
    }


    // responsavel por enviar o e-mail
    // -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    public function smtp_enviar_email(){

        //inicializo a classe do PHPMailer
        $mail             	= 	new PHPMailer();

        //defino os parametros que vou utilizar
        $body				= $this->mensagem;
        $emailsender		= $this->smtp_email;
        $nomesender			= $this->enviadopor;
        $destino			= $this->destino;
        $assunto			= $this->assunto;

        $mail->IsSMTP(); 										// telling the class to use SMTP
        $mail->Host       	= $this->smtp;						// SMTP server
        $mail->SMTPDebug  	= 1;                     			// enables SMTP debug information (for testing)
        $mail->SMTPAuth   	= true;                  			// enable SMTP authentication
        $mail->Port       	= $this->smtp_port;                 // set the SMTP port
        $mail->Username   	= $this->smtp_email; 				// SMTP account username
        $mail->Password   	= $this->smtp_senha;     		    // SMTP account password
        $mail->CharSet 		= 'UTF-8';

        //crio o corpo HTML p/ o envio da mensagem
        $corpo = $this->create_html_email();

        $mail->From 	  = $this->smtp_email;
        $mail->FromName   = $nomesender;

        $mail->Subject    = $this->assunto;
        $mail->AltBody    = "Caso não visualize essa mensagem entre em contato conosco.";


        $mail->MsgHTML($corpo);
        $mail->AddReplyTo($this->replay_mail, $this->replay_name);


        $mail->AddAddress($destino[0]['email'], $destino[0]['nome']);
        //crio um contador p/ enviar os e-mails em cópia
        if(count($destino) > 1){
            unset($destino[0]);
            foreach($destino as $valor){
                $mail->AddBCC($valor['email'], $valor['nome']);
            }
        }

        if($mail->Send()){
            echo $this->alert_toast(1);
        }else{
            echo $this->alert_toast(0);
        }

        return;
    }
    // ProSendermail()::smtp_enviar_email()
    //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=


    // responsavel por criar o corpo html do meu e-mail
    // -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    public function create_html_email(){

        $html = '';
        $html .= '<html>';
        $html .= '<head>';
        $html .= '<title></title>';
        $html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
        $html .= '</head>';
        $html .= '<body bgcolor="#f0ecec" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">';
        $html .= '<div style="background-color:#f0ecec;">';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td height="30">';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '<table align="center" width="650" border="0" cellpadding="30" cellspacing="0" bgcolor="#ffffff">';
        $html .= '<tr>';
        $html .= '<td colspan="8" bgcolor="#ffffff">';
        $html .= $this->mensagem;
        $html .= "<br><br>";
        $html .= 'Obrigado.';
        $html .= "<br><br>";
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td height="30">';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '</body>';
        $html .= '</html>';

        return $html;
    }
    // ProSendermail()::create_html_email()
    //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=


    // responsavel exibir a mensagem de alerta
    // -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    private function alert_toast($bollean){

        switch ($bollean) {
            case 1:
                $dep =
                "<script>
                $.toast({
                  heading: 'SUCESSO',
                  text: 'Seu e-mail foi enviado com sucesso!',
                  hideAfter: false,
                  position: 'bottom-right',
                  icon: 'success'
                })
                  </script>";
                break;
            case 0:
                $dep =
                "<script>
                $.toast({
                  heading: 'Erro Interno',
                  text: 'OPS, ALGO DEU ERRADO, SEU EMAIL NÃO FOI ENVIADO POR ALGUM PROBLEMA INTERNO! TENTE NOVAMENTE MAIS TARDE.',
                  hideAfter: false,
                  position: 'bottom-right',
                  icon: 'error'
                })
                  </script>";
                break;
        }
        return $dep;
    }
    // ProSendermail()::alert_toast()
    //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=


    // cria a lista de e-mail p/ enviar o e-mail
    // -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    public function cria_lista_emails($emails){
        $temp = explode(';', $emails);

        $dest = array();
        $i = 0;

        foreach($temp as $valor){
            if($valor != ''){
                $dest[$i]['nome'] = $valor;
                $dest[$i]['email'] = $valor;

                $i++;
            }
        }

        return $dest;
    }
    // ProSendermail()::cria_lista_emails()
    //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
}
?>
