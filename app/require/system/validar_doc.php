<?php
class validar_doc{

	//reponsalvel por tirar os sinais do documento
    function trocar_sinais($doc){
    	
    	$doc = preg_replace("/[^0-9]/", "", $doc);
    	return $doc;
    }
    
    
    //verifica se o documento é verdadeiro, ou seja, o usuario não digitou 000.000.000-00 .....
    function verifica_doc_falso($doc, $tamanho){
		
		for($i = 0; $i <= 9; $i++) {
	    	
	    	if(str_repeat($i, $tamanho) == $doc){
	    		return true;
		    	
	    	}
	    }
    }
    
    
    //responsavel por verificar o cpf
    //para validar precisamos tirar o sinal dos numeros
    //a base do calculo são os 2 digitos verificadores
    function cpf($cpf){
    	$cpf = $this->trocar_sinais($cpf);
    	$cpf = trim($cpf);
    	
    	
    	$digito1 = 0;
    	$digito2 = 0; 
    	
    	if(empty($cpf) || strlen($cpf) != 11){
	    	return false;
    	}
    	
    	if($this->verifica_doc_falso($cpf, 11)){
    		return false;
    	}
    	
    	//crio um laço dos numeros para o digito1
    	//multiplo pela ordem decrecente començando por 10
    	for($i = 0 ,$d = 10; $i <= 8; $i++, $d--){
	    	$digito1 += $cpf[$i] * $d;
    	}
    	
    	//crio um laço dos numeros para o digito2
    	//multiplo pela ordem decrecente començando por 10
    	for($i = 0 ,$d = 11; $i <= 9; $i++, $d--){
	    	$digito2 += $cpf[$i] * $d;
    	}
    	
    	
    	//divido a soma do digito1 por 11 e pego o valor do resto e subtraio de 11
    	$resultado1 = (($digito1%11) < 2) ? 0 : 11-($digito1%11);
    	
    	//faco a mesa coisa com o digito2
    	$resultado2 = (($digito2%11) < 2) ? 0 : 11-($digito2%11);
    	
    	if($resultado1 != $cpf[9] || $resultado2 != $cpf[10]){
    		return false;
	    	
    	}
    	
    	return true;
    	
	}
	
	
	//responsavel por verificar o cnpj
	function cnpj($cnpj){
		$cnpj = $this->trocar_sinais($cnpj);
    	$cnpj = trim($cnpj);
    	
    	$digito1 = 0;
    	$digito2 = 0; 
    	
    	if(empty($cnpj) || strlen($cnpj) != 14){
	    	return false;
    	}
    	
    	if($this->verifica_doc_falso($cnpj, 14)){

    		return false;
    	}
    	
    	
    	//valido o digito1
    	for($i = 0 , $d = 5; $i < 12; $i++){
	    	
	    	$digito1 += $cnpj[$i] * $d;
	    	$d = ($d == 2) ? 9 : $d - 1;	
    	}
    	
    	//valido o digito2
    	for($i = 0 ,$d = 6; $i < 13; $i++){
	    	
	    	$digito2 += $cnpj[$i] * $d;	
	    	$d = ($d == 2) ? 9 : $d - 1;	
    	}
 	
		//divido a soma do digito1 por 11 e pego o valor do resto e subtraio de 11
    	$resultado1 = (($digito1%11) < 2) ? 0 : 11-($digito1%11);
    	
    	//faco a mesa coisa com o digito2
    	$resultado2 = (($digito2%11) < 2) ? 0 : 11-($digito2%11);
    	
    	
       	if($resultado1 != $cnpj[12] || $resultado2 != $cnpj[13]){
    		return false;
	    	
    	}

    	return true;
    }
}



if(isset($_POST['cnpj']) && $_POST['cnpj'] !=''){
	$cnpj = $_POST['cnpj'];
	
	$validar = new validar_doc;
	
	if($validar->cnpj($cnpj)){
	   $dados['sucesso'] = true;
    }
    else{
    	$dados['sucesso'] = false;
    }
    
    echo json_encode($dados);
	
}


if(isset($_POST['cpf']) && $_POST['cpf'] !=''){
	$cpf = $_POST['cpf'];
	
	$validar = new validar_doc;
	
	if($validar->cpf($cpf)){
	   $dados['sucesso'] = true;
    }
    else{
    	$dados['sucesso'] = false;
    }
    
    echo json_encode($dados);
	
}
?>