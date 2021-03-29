<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

/*
$dados = array();
$dados['usuario'] ='site';
$dados['senha'] ='site';
$dados['dominio'] ='SAGAMOTO';
$dados['nome'] ='Mauricio Stasiak Orloski';
$dados['dataNascimento'] ='31/03/1976';
$dados['email'] ='mauricio.so@sansu.com.br';
$dados['dddResidencial'] ='41';
$dados['telefoneResidencial'] ='30951235';
$dados['dddComercial'] ='41';
$dados['telefoneComercial'] ='33124312';
$dados['dddCelular'] ='41';
$dados['telefoneCelular'] ='96721536';
$dados['tipoLogradouro'] ='Rua';
$dados['logradouro'] ='José Sebastião Baltazar';
$dados['numero'] ='143';
$dados['bairro'] ='CIC';
$dados['cep'] ='81850420';
$dados['cidade'] ='95780000';
$dados['estado'] ='PR';
$dados['assunto'] ='teste';
$dados['observacao'] ='observacao';
$dados['novoUsado'] ='NOVO';
$dados['grupoEvento'] ='OPORTUNIDADE';
$dados['tipoEvento'] ='INTERNET';
$dados['midia'] ='999';
$dados['palavraChave'] ='aa';
$dados['idEmpresa'] ='1';
$dados['idUsuario'] ='0';
$dados['idCliente'] ='0';
$dados['cpfCnpj'] ='0';
$dados['idEvento'] ='0';
$dados['status'] ='aa';
$dados['classificacao'] ='aaa';
$dados['prioridade'] ='';
*/


$params = [
	'usuario' => 'site', 
	'senha' => 'site',
	'dominio' => 'SAGAMOTO',
	'nome' => 'andre toniati',
	'dataNascimento' => '26/02/1988',
	'dddResidencial' => '11',
	'telefoneResidencial' => '1111111111111',
	'assunto' => 'assunto',
	'observacao' => 'observacao',
	'grupoEvento' => 'OPORTUNIDADE',
	'tipoEvento' => 'LEAD',
	'midia' => 'INTERNET',
	'origem' => 'SITE SAGA',
	'idEmpresa' => '5'
];


/*
$params = [
	'usuario' => 'site', 
	'senha' => 'site',
	'dominio' => 'SAGAMOTO',
	'nome' => 'Eugenio',
	'email' => 'eugenio@procriativo.com.br',
	'telefoneCelular' => '1123559711',
	'assunto' => 'Web Service Abracy - Contato',
	'observacao' => 'mensagem de observacao',
	'grupoEvento' => 'OPORTUNIDADE',
	'tipoEvento' => 'LEAD',
	'midia' => 'INTERNET',
	'origem' => 'SITE SAGA',
	'idEmpresa' => '1'
  ];
*/


$client = new SoapClient('http://187.115.64.202:8080/CollaborativeWS-CollaborativeWS/CollaborativeEventoService?wsdl');
$function = 'gerarEventoV2';

$result = $client->__soapCall($function, array($params));
print_r($result);
?>
