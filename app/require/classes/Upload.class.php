<?php
class Upload {

	// classe responsavel por realizar upload de arquivos
	// desenvolvido por Gabriel Estival
	// ultima atualizacao em 28/08/2019
	// VERSAO 1.2


	//funcao responsavel por realizar o upload dos arquivos
	//@param string $pasta
	//@param int $tamanho
	//@param array $extensao
	//@param boleano $renomeia
	//@param string $arquivo
	//@return $nome_final
	function uploadArquivo($pasta, $tamanho, $extensao, $renomeia, $arquivo){

		$_UP['pasta'] = $pasta;
		$_UP['tamanho'] = 1024 * 1024 * $tamanho;
		$_UP['extensoes'] = $extensao;
		$_UP['renomeia'] = $renomeia;

		$_UP['erros'][0] = 'Não houve erro';
		$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
		$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
		$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
		$_UP['erros'][4] = 'Não foi feito o upload do arquivo';


		if($_FILES[''.$arquivo.'']['name'] == ""){
		}
		else{
			if($_FILES[''.$arquivo.'']['error'] !=0){
				die("Não foi possivel fazer o upload, erro<br />" . $_UP['erros'][$_FILES[''.$arquivo.'']['error']]);
				exit;
			}

			@$ext = strtolower(end(explode('.', $_FILES[''.$arquivo.'']['name'])));
			if (array_search($ext, $_UP['extensoes']) === false) {
				echo "<script>alert('Por favor, envie arquivos com as seguintes extensões: $ext.');</script><script>history.back();</script>";
				exit;
			}
			else if ($_UP['tamanho'] < $_FILES[''.$arquivo.'']['size']) {
				echo "<script>alert('O arquivo enviado é muito grande, envie arquivos de até $tamanho Mb.');</script><script>history.back();</script>";
				exit;
			}
			else{
				if($_UP['renomeia'] == true) {
					$nome_final = ''.uniqid().'.'.$ext.'';
				}
				else{
					$nome_final = $_FILES[''.$arquivo.'']['name'];
				}


				if(move_uploaded_file($_FILES[''.$arquivo.'']['tmp_name'], $_UP['pasta'] . $nome_final)) {
					return $nome_final;

				}
				else{
					echo "<script>alert('Não foi possível enviar o arquivo, tente novamente.');</script><script>history.back();</script>";

					return false;
					exit;
				}
			}
		}
	}

}
?>
