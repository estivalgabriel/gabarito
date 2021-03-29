<?php
//responsavel pela acóes do login
class HomeModel extends MainModel{

	public $retorno;
	public $bd;


	public function __construct($bd = false, $controller = null) {
		//$this->bd = $bd;

		// Configura o controlador
		$this->controller = $controller;
		// Configura os parâmetros
		$this->parametros = $this->controller->parametros;
	}

<<<<<<< HEAD
	// acao responsavel por enviar o e-mail
    // -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	public function mail_contato(){

		// Verifica se existe o parâmetro "send-form-contato" na URL
		if(chk_array($this->parametros, 0) == 'send-form-contato' ){

			//recebo todos os valores enviados pelo metodo post
			//recebo as variais pelo metodo post
			$nome 		= 	proteger($_POST['nome']);
			$telefone	= 	proteger($_POST['telefone']);
			$email 		= 	proteger($_POST['email']);
			$assunto 	= 	proteger($_POST['assunto']);
			$mensagem	= 	proteger($_POST['mensagem']);

			//chamo classe responsavel por enviar o email
			require_once ABSPATH."/app/require/classes/ProSendermail.class.php";

			$pro_mail = new ProSendermail();

			//crio o corpo html da minha mensagem
			$msg = "Olá!<br><br>";
			$msg .= "Você acaba de receber uma solicitação pelo formulário de contato<br><br>";

			$msg .= "<b>Nome:</b> $nome<br>";
			$msg .= "<b>E-mail:</b> $email<br>";
			$msg .= "<b>Telefone:</b> $telefone<br>";
			$msg .= "<b>Assunto:</b> $assunto<br>";
			$msg .= "<b>Mensagem:</b> $mensagem<br>";


			//determino os valores de cada variavel
			$pro_mail->assunto      = '[CONTATO] Novo lead captado';
			$pro_mail->enviadopor   = 'Site | Nome do site';
			$pro_mail->destino      = $pro_mail->cria_lista_emails('gabriel@procriativo.com.br');
			$pro_mail->origem				= 'URL:'.URL;

			//não é necessário alterar as informações abaixo
			$pro_mail->replay_name  = $nome;
			$pro_mail->replay_mail  = $email;
			$pro_mail->mensagem     = $msg;

			$pro_mail->smtp_enviar_email();

			return;
		}
	}
	// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

=======
>>>>>>> 0bbb26ff51f1aebe77e12dcce125e40df4983ad7
}
