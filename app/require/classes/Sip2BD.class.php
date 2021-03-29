<?php
/*
Sip2BD -  Gerenciamento o banco de dados

@criado por Gabriel Estival
@Copyright Gabriel Estival

@version 1.0 - 28/08/2019
*/

/*
$modelo->bd = new Sip2BD();

INSERIR
$modelo->bd->inserir(
	'tabela',

	// Insere uma linha
	array('campo_tabela' => 'valor', 'outro_campo'  => 'outro_valor'),

	// Insere outra linha
	array('campo_tabela' => 'valor', 'outro_campo'  => 'outro_valor'),

	// Insere outra linha
	array('campo_tabela' => 'valor', 'outro_campo'  => 'outro_valor')
);

ATUALIZAR
$modelo->bd->atualizar(
	'tabela', 'campo_where', 'valor_where',

	// Atualiza a linha
	array('campo_tabela' => 'valor', 'outro_campo'  => 'outro_valor')
);

APAGAR
$modelo->bd->deletar(
	'tabela', 'campo_where', 'valor_where'
);

SELECIONAR
$modelo->bd->selecionar(
	'SELECT * FROM tabela WHERE campo = ? AND outro_campo = ?',
	array( 'valor', 'valor' )
);
*/

class Sip2BD{

	//variaveis do banco de dados

	public $host      = '', 	// Host da base de dados
	       $db_name   = '',    	// Nome do banco de dados
	       $password  = '',     // Senha do usuário da base de dados
	       $user      = '',     // Usuário da base de dados
	       $charset   = '',     // Charset da base de dados
	       $pdo       = null,   // Nossa conexão com o banco de dados
	       $error     = null,   // Configura o erro
	       $debug     = false,  // Mostra todos os erros
	       $last_id   = null;   // Último ID inserido



	//contrutor da classe
	public function __construct(
		$host     = null,
		$db_name  = null,
		$password = null,
		$user     = null,
		$charset  = null,
		$debug    = null
		){

		// Configura as propriedades novamente.
		$this->host     = defined( 'HOSTNAME'    ) ? HOSTNAME    : $this->host;
		$this->db_name  = defined( 'DB_NAME'     ) ? DB_NAME     : $this->db_name;
		$this->password = defined( 'DB_PASSWORD' ) ? DB_PASSWORD : $this->password;
		$this->user     = defined( 'DB_USER'     ) ? DB_USER     : $this->user;
		$this->charset  = defined( 'DB_CHARSET'  ) ? DB_CHARSET  : $this->charset;
		$this->debug    = defined( 'DEBUG'       ) ? DEBUG       : $this->debug;

		// conecta no banco de dados
		$this->conectar();

	}
	// __construct -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=



	//cria a conexão com o PDO
	final protected function conectar() {

		//detalhes da conexão pdo
		$pdo_details  = "mysql:host={$this->host};";
		$pdo_details .= "dbname={$this->db_name};";
		$pdo_details .= "charset={$this->charset};";

		//tentamos conectar
		try{

			$this->pdo = new PDO($pdo_details, $this->user, $this->password);
			// Verifica se devemos debugar
			if($this->debug === true){
				// Configura o PDO ERROR MODE
				$this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
			}

			// Não precisamos mais dessas propriedades
			unset($this->host);
			unset($this->db_name);
			unset($this->password);
			unset($this->user);
			unset($this->charset);

		}
		catch(PDOException $e){

			// Verifica se devemos debugar
			if($this->debug === true){

				// Mostra a mensagem de erro
				echo "Erro: " . $e->getMessage();
			}

			die();
		}

	}
	// conectar -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=


	// criando uma consulta no pdo
	public function selecionar($stmt, $data_array = null){

		// prepara a consulta e executa a consulta
		$query      = $this->pdo->prepare($stmt);
		$check_exec = $query->execute($data_array);

		// verifico se a consulta aconteceu
		if($check_exec){

			// Retorna a consulta
			return $query;

		}
		else{

			// configuro o erro
			$error       = $query->errorInfo();
			$this->error = $error[2];

			// Retorna falso
			return false;
		}
	}
	// consultar -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=



	//insere os valores no banco de dados
	//tenta retornar o útimo id enviado
	public function inserir($table) {

		// configura a array de colunas
		$colunas = array();

		// configura o valor inicial do modelo
		$place_holders = '(';

		// configura a array de valores
		$valores = array();

		// O $j assegura que colunas serão configuradas apenas uma vez
		$j = 1;

		// recupera os valores enviados
		$data = func_get_args();

		//é necessario enviar uma array de chaves e valores
		if(!isset($data[1]) || !is_array($data[1])) {
			return;
		}

		// faz um laço com os argumentos enviados
		for($i = 1; $i < count($data); $i++) {

			// Obtém as chaves como colunas e valores como valores
			foreach ( $data[$i] as $col => $val ) {

				// A primeira volta do laço configura as colunas
				if ( $i === 1 ) {
					$colunas[] = "`$col`";
				}

				if ( $j <> $i ) {
					// Configura os divisores
					$place_holders .= '), (';
				}

				// Configura os place holders do PDO
				$place_holders .= '?, ';

				// Configura os valores que vamos enviar
				$valores[] = $val;

				$j = $i;
			}

			// Remove os caracteres extra dos place holders
			$place_holders = substr( $place_holders, 0, strlen( $place_holders ) - 2);
		}

		// Separa as colunas por vírgula
		$colunas = implode(', ', $colunas);

		// Cria a declaração para enviar ao PDO
		$stmt = "INSERT INTO $table ($colunas) VALUES $place_holders)";

		// insere os valores
		$inserir = $this->selecionar($stmt, $valores);

		// Verifica se a consulta foi realizada com sucesso
		if($inserir) {

			// Verifica se temos o último ID enviado
			if(method_exists($this->pdo, 'lastInsertId') && $this->pdo->lastInsertId()) {
				// Configura o último ID
				$this->last_id = $this->pdo->lastInsertId();
			}

			// Retorna a consulta
			return $this->last_id;
		}
		return;
	}
	// inserir -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=





	//atualiza o banco de dados
	//atualiza uma linha da tabela baseada em um campo
	public function atualizar( $table, $where_field, $where_field_value, $values ) {

		// obrigatório enviar todos os parametros do metodo
		if( empty($table) || empty($where_field) || empty($where_field_value) ){
			return;
		}

		//inicio a declaração
		$stmt = " UPDATE `$table` SET ";

		//configuro a array de valores
		$set = array();

		//configura a declaração do WHERE campo=valor
		$where = " WHERE `$where_field` = ? ";

		// Você precisa enviar um array com valores
		if ( ! is_array( $values ) ) {
			return;
		}

		//configura as colunas a atualizar
		foreach ( $values as $column => $value ) {
			$set[] = " `$column` = ?";
		}

		//separa as colunas por vírgula
		$set = implode(', ', $set);

		//concatena a declaração
		$stmt .= $set . $where;

		//configura o valor do campo que vamos buscar
		$values[] = $where_field_value;

		//carante apenas números nas chaves do array
		$values = array_values($values);

		//atualiza
		$update = $this->selecionar( $stmt, $values);

		//verifica se a consulta está OK
		if($update) {
			// Retorna a consulta
			return $update;
		}

		return;
	}
	// atualizar -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=


	//deleta uma linha do banco de dados
	public function deletar( $table, $where_field, $where_field_value ) {
		// obrigatório enviar todos os parametros do metodo
		if ( empty($table) || empty($where_field) || empty($where_field_value)  ) {
			return;
		}

		//inicio a declaraçao
		$stmt = " DELETE FROM `$table` ";

		//configura a declaração WHERE campo=valor
		$where = " WHERE `$where_field` = ? ";

		//concatena tudo
		$stmt .= $where;

		//o valor que vamos buscar para apagar
		$values = array( $where_field_value );

		//apaga
		$delete = $this->selecionar( $stmt, $values );

		// Verifica se a consulta está OK
		if ( $delete ) {
			// Retorna a consulta
			return $delete;
		}

		return;
	}
	// deletar -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
}
