<?php
/*
* MainModel -  Modelo Geral das Main
*
* @criado por Gabriel Estival
* @Copyright Gabriel Estival
* @version 1.0 - 28/08/2019
*
*/
class MainModel{

	//os dados de formulário de envio
	public $dados_form;
	//mensagem de feedback para os formulário
	public $msg_form;
	// mensagem de confirmação para apagar dados do formulário
	public $confirma_form;
	//objeto da conexão com pdo
	public $bd;
	//controler gerado pela model
	public $controller;
	//Parâmetros da URL
	public $parametros;
	//dados do usuario caso exista sistema de autenticacao
	public $dados_usuario;
}
?>
