<?php
// MainController - Todos os controllers deverão estender essa classe
class MainController{


	//$pdo
	//nossa conexao com o banco de dados. Manterá o objeto pdo.
	public $bd;

	//título das páginas
	public $titulo;

	//descricao das páginas
	public $descricao;

	//palavras chaves das páginas
	public $keyword;

	//palavras chaves das páginas
	public $robots = 'index, follow';

	//palavras chaves das páginas
	public $author = AUTOR;

	//se a página precisa de login
	public $login_required = false;

	//permissao necessária
	public $permission_required = 'any';

	//$acao
	public $acao;

	//$parametros
	public $parametros = array();


	//construtor da classe
	//configura as propriedades e métodos da classe
	public function __construct ( $parametros = array()) {

		//inicializa o banco de dados
		//$this->bd = new Sip2BD();

		// parâmetros
		$this->parametros = $parametros;

		// Verifica o login
		//$this->check_usuariologin();

	}
	// __construct -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=



	//carrega a models
	//carrega os modelos presentes nas pastas models
	public function load_model($model_name = false ) {

		// um arquivio deverá ser enviado
		if(!$model_name) return;

		// garante que o nome do modelo tenha letras minúsculas
		$model_name =  strtolower($model_name);

		// inclui o arquivo
		$model_path = ABSPATH .'/app/models/' . $model_name . '.php';

		//verifica se o arquivo existe
		if ( file_exists( $model_path ) ) {

			//inclui o arquivo
			require_once $model_path;

			//remove os caminhos do arquivo (se tiver algum)
			$model_name = explode('/', $model_name);

			//pega só o nome final do caminho
			$model_name = end($model_name);

			// remove caracteres inválidos do nome do arquivo
			$model_name = preg_replace( '/[^a-zA-Z0-9]/is', '', $model_name);

			// verifica se a classe existe
			if (class_exists($model_name)) {

				// Retorna um objeto da classe
				return new $model_name( $this->bd, $this);
			}
			return;

		}

	}
	// load_model -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

}
?>
