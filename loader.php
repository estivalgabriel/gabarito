<?php
//evita que esse arquivo seja aberto diretamente
if(!defined('ABSPATH')){ exit; }

//inicializo a sessao no site
session_start();

//verifica se o modo debug está ativado
if(!defined('DEBUG') || DEBUG === false ){
    error_reporting(0);
	ini_set("display_errors", 0);
}
else{
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
}

//acesso todas as informações armazenadas nas sessoes
if(isset($_GET['session'])){
	echo '<pre>';
	print_r($_SESSION);
	echo '</pre>';
}
//apago as sessoes armazenadas
if(isset($_GET['session_destroy'])){
	unset($_SESSION[session_id()]);
	session_destroy();
	session_regenerate_id();
}

//adiciono as funcoes globais
require_once ABSPATH .'/app/require/system/global-functions.php';

// inicializo a aplicacao
$sip2 = new Procriativo();

/*
$spi2->url_testserver = '';
*/

//adiciono a interface de desenvolvedor
require_once ABSPATH .'/app/require/system/developer.php';
?>
