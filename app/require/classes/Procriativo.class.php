<?php
/*
<<<<<<< HEAD
* SIP2 - Gerencia Models, Controllers e Views
*
* @criado por Gabriel Estival
* @Copyright Gabriel Estival
* @version 1.0 - 28/08/2019
*
*/
class Procriativo{

	// receberá o valor do controlador vindo da url
	// exemplo.com/controller
	private $controlador;

	// receberá o valor da ação vindo da url
	// exemplo.com/controller/acao
	private $acao;

	// receberá um array dos parametros via urldecode
	// exemplo.com/controller/acao/paramq/param2/param...
	private $parametros;

	// caminho da página nao encontrada
	private $notfound = '/app/views/_404.php';

	// caminho da página offline
	private $offline = '/app/views/_offline.php';

	// define se a aplicação está ativa ou nao
	private $status_aplicacao = 1;

	//url padrão para desenvolvimento local
	public $url_localhost = 'localhost';

	//url padrao para testar online
	public $url_testserver = 'upload.gabrielestival.com.br';

	// obtem os valores do controlador, ação e parâmetro.
	// configura o controller e a acao(metodo)
	public function __construct(){

		//defino a constante URL
		define('URL', $this->check_url());

		//defino as urls das imagens, js e css
		define( 'PATH_IMG', URL . '/public/assets/images' );
		//caminho para pasta padrao de javascript
		define( 'PATH_JS', URL . '/public/assets/js' );
		//caminho para pasta padrao de css
		define( 'PATH_CSS', URL . '/public/assets/css' );

		//valido a aplicação
		$this->status_aplicacao = $this->start_aplication();

		//verifico se a aplicação está apta a ser utilizada
		if($this->status_aplicacao == 0){
			// pagina nao encontrada
			require_once ABSPATH . $this->offline;
			return;
		}

		// obtém os valores do controlador, ação e parâmetros da URL.
		// configura as propriedades da classe.
		$this->get_url_data();

		// verifica se o controlador existe
		// caso contrário adiciona o controlador padrao localizado controllers/pro-controller.php e chama o método index().
		if(!$this->controlador){
			//adiciona o controlador padrão
			require_once ABSPATH . '/app/controllers/home-controller.php';

			// cria o objeto do controlador "procriativo-controller.php"
			// este controlador deverá ter uma classe chamada ProcriativoController
			$this->controlador = new HomeController();

			// executa o método index()
			$this->controlador->index();
			return;
		}


		//se o controlador nao existir nao fazemos nada
		if (!file_exists( ABSPATH .'/app/controllers/'.$this->controlador.'.php' ) ) {
			// pagina nao encontrada
			require_once ABSPATH . $this->notfound;
			return;
		}

		//inclui o controlador
		require_once ABSPATH .'/app/controllers/'.$this->controlador.'.php';

		// remove caracteres inválidos do nome do controlador para gerar o nome
		$this->controlador = preg_replace( '/[^a-zA-Z]/i', '', $this->controlador);

		//se a classe do controllador nao existir nao faremos nada
		if (!class_exists($this->controlador)) {
			//pagina nao encontrada
			require_once ABSPATH . $this->notfound;
			return;
		}

		// Cria o objeto da classe do controlador e envia os parâmentros
		$this->controlador = new $this->controlador($this->parametros);

		// Remove caracteres inválidos do nome da ação (método)
		$this->acao = preg_replace( '/[^a-zA-Z]/i', '', $this->acao );

		// Se o método indicado existir, executa o método e envia os parâmetros
		if (method_exists( $this->controlador, $this->acao)) {
			$this->controlador->{$this->acao}( $this->parametros );
			return;
		}

		// sem ação chamamos o método index
		if (!$this->acao && method_exists( $this->controlador, 'index' )) {
			$this->controlador->index( $this->parametros );
			return;
		}

		return;
	}
	// Procriativo::__construct() -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=



	//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	//obtem os parametro de $_GET['path']
	//Obtém os parâmetros de $_GET['path'] e configura as propriedades
	//$this->controlador, $this->acao e $this->parametros
	//A URL deverá ter o seguinte formato:
	//http://www.example.com/controller/acao/parametro1/parametro2/etc...
	public function get_url_data(){

		// Verifica se o parâmetro path foi enviado
		if(isset($_GET['path'])) {
			// Captura o valor de $_GET['path']
			$path = $_GET['path'];

			// Limpa os dados
            $path = rtrim($path, '/');
            $path = filter_var($path, FILTER_SANITIZE_URL);

   			// Cria um array de parâmetros
   			$path = explode('/', $path);

   			// Configura as propriedades
			$this->controlador  = chk_array( $path, 0 );
			$this->controlador .= '-controller';
			$this->acao         = chk_array( $path, 1 );

			// Configura os parâmetros
			if(chk_array($path, 2 )) {
				unset($path[0]);
				unset($path[1]);
				// Os parâmetros sempre virão após a ação
				$this->parametros = array_values( $path );
			}

			//print_r($path);
			//echo($this->acao);
		}
	}
	// Procriativo::get_url_data() -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

	/*
	* exibo as informações de desenvolvimento e créditos do projeto
	*/
	public function info_projeto(){
		$arquivo = fopen(ABSPATH.'/app/require/system/copyright.txt','r');
		while(true) {
			$linha = fgets($arquivo);
			if ($linha==null) break;
			echo $linha;
		}
		fclose($arquivo);
	}
	// info_projeto -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=


	/*
	* edita o arquivo serial p/ alterar a chave de segurança
	*
	* @param string $conteudo	Informacao contida no aquivo serial
	*
	* @return bollean
	*/
	public function editar_key($conteudo){
		$arquivo = ABSPATH.'/app/require/system/serial.txt';
		file_put_contents($arquivo, $conteudo);
		return true;
	}
	// editar_key -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=



	/*
	* responsavel por verificar a url e definir qual caminho utilizar
	*
	* @return string	A url do projeto
	*/
	public function check_url(){

		$url = $_SERVER['SERVER_NAME'];
		$url = trim($url);

		//faço a verificação de igualdade entre urls
		switch ($url) {
			case $this->url_localhost:
				$temp_url = PROTOCOLO_LOCALHOST.$this->url_localhost.DIRETORIO_LOCALHOST;
				break;
			case $this->url_testserver:
				$temp_url = PROTOCOLO_TESTSERVER.$this->url_testserver.DIRETORIO_TESTSERVER;
				break;
			case URL_REAL:
				$temp_url = PROTOCOLO_REAL.URL_REAL.DIRETORIO_REAL;
				break;
			default:
				$temp_url = 'urlnaodefinida.com.br';
		}

		return $temp_url;
	}
	// Procriativo::check_url() -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=




	//valida a aplicação na internet automaticamente,
	//mesmo que o modo debug esteja habilitado
	private function start_aplication(){

		$url = $_SERVER['SERVER_NAME'];
		$url = trim($url);

		if($url == $this->url_localhost || $url == $this->url_testserver){
			$status = 1;
		}
		else{
			$status = 0;
		}

		if($url == URL_REAL){
			$arquivo = ABSPATH. '/app/require/system/serial.txt';
			$key = file_get_contents($arquivo);

			$key = trim($key);

			if((string)$key == $this->private_key()){
				$status = 1;
			}
			else{
				$status = 0;
				echo '<p>existe um problema com o certificado</p>';
			}
		}
		return $status;
	}

	//crio a hash e a privatekey
	public function hash(){

		$url = $_SERVER['SERVER_NAME'];
		$url = trim($url);

		$hash = md5(NOME_PROJETO.$url);

		return $hash;
	}
	public function private_key(){

		$key = md5(NOME_PROJETO.URL_REAL);

		return $key;
	}

}
=======
 * SIP2 - Gerencia Models, Controllers e Views
 *
 * @criado por Eugenio Toniati
 * @Copyright Procriativo Agência Multimidia
 * @version 1.0 - 25/12/2015
 *
 * @version 1.1 - 08/06/2016
 * @version 1.2 - 13/12/2018
 */
class Procriativo
{
    // receberá o valor do controlador vindo da url
    // exemplo.com/controller
    private $controlador;
    // receberá o valor da ação vindo da url
    // exemplo.com/controller/acao
    private $acao;
    // receberá um array dos parametros via urldecode
    // exemplo.com/controller/acao/paramq/param2/param...
    private $parametros;
    // caminho da página nao encontrada
    private $notfound = '/app/views/_404.php';
    // caminho da página offline
    private $offline = '/app/views/_offline.php';
    // define se a aplicação está ativa ou nao
    private $status_aplicacao = 1;
    //url padrão para desenvolvimento local
    public $url_localhost = 'localhost';
    //url padrao para testar online
    public $url_testserver = 'upload.procriativo.com.br';
    // obtem os valores do controlador, ação e parâmetro.
    // configura o controller e a acao(metodo)
    public function __construct()
    {
        //defino a constante URL
        define('URL', $this->check_url());
        //defino as urls das imagens, js e css
        define('PATH_IMG', URL . '/public/assets/images');
        //caminho para pasta padrao de javascript
        define('PATH_JS', URL . '/public/assets/js');
        //caminho para pasta padrao de css
        define('PATH_CSS', URL . '/public/assets/css');
        //valido a aplicação
        $this->status_aplicacao = $this->start_aplication();
        //verifico se a aplicação está apta a ser utilizada
        if ($this->status_aplicacao == 0) {
            // pagina nao encontrada
            require_once ABSPATH . $this->offline;
            return;
        }
        // obtém os valores do controlador, ação e parâmetros da URL.
        // configura as propriedades da classe.
        $this->get_url_data();
        // verifica se o controlador existe
        // caso contrário adiciona o controlador padrao localizado controllers/pro-controller.php e chama o método index().
        if (!$this->controlador) {
            //adiciona o controlador padrão
            require_once ABSPATH . '/app/controllers/home-controller.php';
            // cria o objeto do controlador "procriativo-controller.php"
            // este controlador deverá ter uma classe chamada ProcriativoController
            $this->controlador = new HomeController();
            // executa o método index()
            $this->controlador->index();
            return;
        }
        
        //se o controlador nao existir nao fazemos nada
        if (!file_exists(ABSPATH . '/app/controllers/' . $this->controlador . '.php')) {
            // pagina nao encontrada
            require_once ABSPATH . $this->notfound;
            return;
        }
        //inclui o controlador
        require_once ABSPATH . '/app/controllers/' . $this->controlador . '.php';
        // remove caracteres inválidos do nome do controlador para gerar o nome
        $this->controlador = preg_replace('/[^a-zA-Z]/i', '', $this->controlador);
        //se a classe do controllador nao existir nao faremos nada
        if (!class_exists($this->controlador)) {
            //pagina nao encontrada
            require_once ABSPATH . $this->notfound;
            return;
        }
        // Cria o objeto da classe do controlador e envia os parâmentros
        $this->controlador = new $this->controlador($this->parametros);
        // Remove caracteres inválidos do nome da ação (método)
        $this->acao        = preg_replace('/[^a-zA-Z]/i', '', $this->acao);
        // Se o método indicado existir, executa o método e envia os parâmetros
        if (method_exists($this->controlador, $this->acao)) {
            $this->controlador->{$this->acao}($this->parametros);
            return;
        }
        // sem ação chamamos o método index
        if (!$this->acao && method_exists($this->controlador, 'index')) {
            $this->controlador->index($this->parametros);
            return;
        }
        return;
    }
    // Procriativo::__construct() -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    
    //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    //obtem os parametro de $_GET['path']
    //Obtém os parâmetros de $_GET['path'] e configura as propriedades
    //$this->controlador, $this->acao e $this->parametros
    //A URL deverá ter o seguinte formato:
    //http://www.example.com/controller/acao/parametro1/parametro2/etc...
    public function get_url_data()
    {
        // Verifica se o parâmetro path foi enviado
        if (isset($_GET['path'])) {
            // Captura o valor de $_GET['path']
            $path              = $_GET['path'];
            // Limpa os dados
            $path              = rtrim($path, '/');
            $path              = filter_var($path, FILTER_SANITIZE_URL);
            // Cria um array de parâmetros
            $path              = explode('/', $path);
            // Configura as propriedades
            $this->controlador = chk_array($path, 0);
            $this->controlador .= '-controller';
            $this->acao = chk_array($path, 1);
            // Configura os parâmetros
            if (chk_array($path, 2)) {
                unset($path[0]);
                unset($path[1]);
                // Os parâmetros sempre virão após a ação
                $this->parametros = array_values($path);
            }
            //print_r($path);
            //echo($this->acao);
        }
    }
    // Procriativo::get_url_data() -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    /*
     * exibo as informações de desenvolvimento e créditos do projeto
     */
    public function info_projeto()
    {
        $arquivo = fopen(ABSPATH . '/app/require/system/copyright.txt', 'r');
        while (true) {
            $linha = fgets($arquivo);
            if ($linha == null)
                break;
            echo $linha;
        }
        fclose($arquivo);
    }
    // info_projeto -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    
    /*
     * edita o arquivo serial p/ alterar a chave de segurança
     *
     * @param string $conteudo Informacao contida no aquivo serial
     *
     * @return bollean
     */
    public function editar_key($conteudo)
    {
        $arquivo = ABSPATH . '/app/require/system/serial.txt';
        file_put_contents($arquivo, $conteudo);
        return true;
    }
    // editar_key -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    
    /*
     * responsavel por verificar a url e definir qual caminho utilizar
     *
     * @return string A url do projeto
     */
    public function check_url()
    {
        $url = $_SERVER['SERVER_NAME'];
        $url = trim($url);
        //faço a verificação de igualdade entre urls
        switch ($url) {
            case $this->url_localhost:
                $temp_url = PROTOCOLO_LOCALHOST . $this->url_localhost . DIRETORIO_LOCALHOST;
                break;
            case $this->url_testserver:
                $temp_url = PROTOCOLO_TESTSERVER . $this->url_testserver . DIRETORIO_TESTSERVER;
                break;
            case URL_REAL:
                $temp_url = PROTOCOLO_REAL . URL_REAL . DIRETORIO_REAL;
                break;
            default:
                $temp_url = 'urlnaodefinida.com.br';
        }
        return $temp_url;
    }
    // Procriativo::check_url() -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    
    
    //valida a aplicação na internet automaticamente,
    //mesmo que o modo debug esteja habilitado
    private function start_aplication()
    {
        $url = $_SERVER['SERVER_NAME'];
        $url = trim($url);
        if ($url == $this->url_localhost || $url == $this->url_testserver) {
            $status = 1;
        } else {
            $status = 0;
        }
        if ($url == URL_REAL) {
            $arquivo = ABSPATH . '/app/require/system/serial.txt';
            $key     = file_get_contents($arquivo);
            $key     = trim($key);
            if ((string) $key == $this->private_key()) {
                $status = 1;
            } else {
                $status = 0;
                echo '<p>existe um problema com o certificado</p>';
            }
        }
        return $status;
    }
    //crio a hash e a privatekey
    public function hash()
    {
        $url  = $_SERVER['SERVER_NAME'];
        $url  = trim($url);
        $hash = md5(NOME_PROJETO . $url);
        return $hash;
    }
    public function private_key()
    {
        $key = md5(NOME_PROJETO . URL_REAL);
        return $key;
    }
}
>>>>>>> 0bbb26ff51f1aebe77e12dcce125e40df4983ad7
