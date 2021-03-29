<?php
if(isset($_GET['developer'])){

	echo'<div style="padding:20px; background: #fffdcd;">';
	echo 'NOME DO PROJETO: '.NOME_PROJETO.'<br>';
	echo 'ANO DO PROJETO: '.ANO_PROJETO.'<br>';
	echo 'URL ACESSADA: '.URL.'<br>';
	echo 'URL REAL: '.URL_REAL.'<br>';

	echo '<br><br>';
	echo 'TEMP KEY: '.$sip2->hash().'<br>';
	//echo 'PRIVATE KEY: '.$sip2->private_key().'<br><br>';

	echo'COMANDOS DISPONÍVEIS: KEY, PRIVATE, SESSION e SESSION_DESTROY<br>';

	if(isset($_GET['private'])){
		echo 'PRIVATE KEY: '.$sip2->private_key().'<br>';
	}

	if(isset($_GET['key'])){
		if($sip2->editar_key($_GET['key'])){
			echo 'Serial alterado.<br>';
		}
	}

	echo'</div>';
}

echo '<!--';
$sip2->info_projeto();
echo '-->';
?>
