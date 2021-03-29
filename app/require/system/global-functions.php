<?php
/*
	Funçoes padrões do site que gerenciam a estrutura do site
	Novas funções devem ser criadas no arquivo function.php

	@developer		Gabriel Estival
	@create_code	28/08/2019
	@last_update	28/08/2019
	@version        0.1
*/
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
require_once(ABSPATH .'/app/require/system/functions.php');
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

/*
Carregar todas as classes padroes.

@param string 	$nome_classe	O nome da classe
*/
//*****************************************************************************
function __autoload($nome_classe) {

	$arquivo = ABSPATH .'/app/require/classes/'.$nome_classe.'.class.php';

	if(!file_exists($arquivo)){
		require_once ABSPATH .'/app/views/_404.php';
		return;
	}
	// Inclui o arquivo da classe
    require_once $arquivo;
}
//*****************************************************************************


/*
Verifica chaves de arrays
Verifica se a chave existe no array e se ela tem algum valor.

@param array  $array O array
@param string $key   A chave do array
@return string|null  O valor da chave do array ou nulo
*/
//*****************************************************************************
function chk_array($array, $key){
	// Verifica se a chave existe no array
	if ( isset( $array[ $key ] ) && ! empty( $array[ $key ] ) ) {
		// Retorna o valor da chave
		return $array[ $key ];
	}
	// Retorna nulo por padrão
	return null;
}
//*****************************************************************************


/*
Função para redirecionar p/ outra página

@param string $url_pagina
*/
//*****************************************************************************
function redirecionar( $url_pagina = null ) {

	if(isset($_GET['url']) && ! empty($_GET['url']) && ! $url_pagina) {
		// Configura a URL
		$url_pagina  = urldecode( $_GET['url'] );
	}

	if($url_pagina){
		// Redireciona
		echo '<meta http-equiv="Refresh" content="0; url=' . $url_pagina . '">';
		echo '<script type="text/javascript">window.location.href = "' . $url_pagina. '";</script>';
		//header('location: ' . $page_uri);
		return;
	}
}
//*****************************************************************************


/*
funcao responsavel por transformar o ID em alfanumerico
*/
//*****************************************************************************
function alphaID($in, $to_num = false, $pad_up = false) {

	// Letras que serão usadas no índice textual
    $index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $base  = strlen($index);

    if ($to_num) {
        // Tradução de texto para número
        $in  = strrev($in);
        $out = 0;
        $len = strlen($in) - 1;
        for ($t = 0; $t <= $len; $t++) {
            $bcpow = bcpow($base, $len - $t);
            $out   = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
        }

        if (is_numeric($pad_up)) {
            $pad_up--;
            if ($pad_up > 0) {
                $out -= pow($base, $pad_up);
            }
        }
    } else {
        // Tradução de número para texto
        if (is_numeric($pad_up)) {
            $pad_up--;
            if ($pad_up > 0) {
                $in += pow($base, $pad_up);
            }
        }

        $out = "";
        for ($t = floor(log10($in) / log10($base)); $t >= 0; $t--) {
            $a   = floor($in / bcpow($base, $t));
            $out = $out . substr($index, $a, 1);
            $in  = $in - ($a * bcpow($base, $t));
        }
        $out = strrev($out);
    }

    return $out;
}
//*****************************************************************************

/*
funcao responsavel por converter as datas

@param date $data		Recebe a data dd/mm/yyyy ou yyyy/mm/dd
@param bollean $iso		Troca entre formato iso ou brasileiro

@return date			Retorna a data convertida
*/
//*****************************************************************************
function converte_data($data, $iso = false){

	if($iso == true){
		$temp = explode('/',$data);
		$temp = array_reverse($temp);
		$temp = implode('-', $temp);

		$data = $temp;
	}
	else{
		$temp = explode('-',$data);
		$temp = array_reverse($temp);
		$temp = implode('/', $temp);

		$data = $temp;
	}
		return $data;
}
//*****************************************************************************


/*
Responsavel por proteger as strings

@param string $string	A string tratada
@param bollean $html	Se tiver que ser desconsiderado as tags html

@return string			String Protegida
*/
//*****************************************************************************
function proteger($string, $html=false){

	//verifico se não é uma array
	if(!is_array($string)){
		$string = preg_replace("/(from|select|insert|delete|where|drop table|show tables|\*|--|\\\\)/","",$string);
		$string = str_replace("<script","",$string);
		$string = str_replace("script>","",$string);
		$string = str_replace("<Script","",$string);
		$string = str_replace("Script>","",$string);

		$string = trim($string);						//limpa espaços vazio
		if($html==false){
			$string = strip_tags($string);				//tira tags html e php
		}
		$string = addslashes($string);					//Adiciona barras invertidas a uma string
	}

	return $string;
}
//*****************************************************************************


/*
deleta a pasta e seus arquivos

@param string $dir 		O diretorio a ser apagado
*/
//*****************************************************************************
function del_pasta($dir){

	if($x = opendir($dir)){
		while(false !== ($file = readdir($x))){
			if($file != "." && $file != ".."){

				$path = $dir."/".$file;

				if(is_dir($path)){
					del_pasta($path);
				}
				else if(is_file($path)){
					unlink($path);
				}
			}
		}
		closedir($x);
	}
	rmdir($dir);
}
//*****************************************************************************
?>
